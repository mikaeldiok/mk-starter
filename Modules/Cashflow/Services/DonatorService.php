<?php

namespace Modules\Cashflow\Services;

use Modules\Cashflow\Repositories\DonationRepository;

use Exception;
use Carbon\Carbon;
use Auth;

use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Cashflow\Imports\DonationsImport;

class DonationService{

    protected $DonationRepository;

    public function __construct(
        DonationRepository $DonationRepository
        )
        {        
        $this->DonationRepository = $DonationRepository;

        $this->module_title = Str::plural(class_basename($this->DonationRepository->model()));

        if(Auth::check()){
            $this->username = Axuth::user()->name;
            $this->userid = Axuth::user()->id;
        }else{
            $this->username = "Guest";
            $this->userid = 0;
        }
    }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        $Donation =$this->DonationRepository->query()->orderBy('order','asc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $Donation,
        );
    }

    public function getList(){

        $Donation =$this->DonationRepository->query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $Donation,
        );
    }


    public function create(){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.$this->username.'(ID:'.$this->userid.')');
        
        $createOptions = $this->prepareOptions();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $createOptions,
        );
    }

    public function store(Request $request){

        $data = $request->all();
        DB::beginTransaction();

        try {
            $DonationObject = $this->DonationRepository->make($data);

            $bank = preg_split('/-/',$data['Donation_bank_name']);

            $DonationObject->Donation_bank_code = $bank[0];
            $DonationObject->Donation_bank_name = $bank[1];

            $DonationObjectArray = $DonationObject->toArray();
            $DonationObjectArray['password'] = Hash::make($data['password']);

            $Donation = $this->DonationRepository->create($DonationObjectArray);

        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__function__)." | '".$Donation->name.'(ID:'.$Donation->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $Donation,
        );
    }

    public function show($id){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->DonationRepository->findOrFail($id),
        );
    }

    public function edit($id){

        $Donation = $this->DonationRepository->findOrFail($id);

        $Donation->installment_ids = json_decode($Donation->installment_ids, true);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$Donation->name.'(ID:'.$Donation->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $Donation,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{
            $Donation = $this->DonationRepository->make($data);

            $updated = $this->DonationRepository->update($Donation->toArray(),$id);

            $updated_Donation = $this->DonationRepository->findOrFail($id);
            
        }catch (Exception $e){
            DB::rollBack();
            report($e);
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_Donation->name.'(ID:'.$updated_Donation->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_Donation,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $Donations = $this->DonationRepository->findOrFail($id);
    
            $deleted = $this->DonationRepository->delete($id);
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$Donations->name.', ID:'.$Donations->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $Donations,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->DonationRepository->trashed(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $Donations= $this->DonationRepository->restore($id);
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$Donations->name.", ID:".$Donations->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $Donations,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $Donations = $this->DonationRepository->findTrash($id);
    
            $deleted = $this->DonationRepository->purge($id);
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$Donations->name.', ID:'.$Donations->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $Donations,
        );
    }

    public function import(Request $request){
                 
        $import = Excel::import(new DonationsImport, $request->file('data_file'));

        
    
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $import,
        );
    }

    public function prepareOptions(){
        
        $banks= [];
        $bank_names= [];

        $raw_banks = config('banks');

        foreach($raw_banks as $raw_bank){
            $banks = Arr::add($banks, $raw_bank['code'].'-'.$raw_bank['name'], $raw_bank['code'].' - '.$raw_bank['name'] );
            $bank_names = Arr::add($bank_names, $raw_bank['name'], $raw_bank['name'] );
        }

        $Donation_types = [
            'institusi'     => 'Institusi',
            'perorangan'     => 'Perorangan',
        ];

        $options = array(
            'banks'         => $banks,
            'bank_names'    => $bank_names,
            'Donation_types' => $Donation_types,
        );

        return $options;
    }
}