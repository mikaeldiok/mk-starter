<?php

namespace Modules\Fund\Services;

use Modules\Fund\Repositories\DonationRepository;
use Modules\Benefactor\Repositories\DonatorRepository;

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


use Modules\Fund\Imports\DonationsImport;

class DonationService{

    protected $donationRepository;
    protected $donatorRepository;

    public function __construct(
        DonationRepository $donationRepository,
        DonatorRepository $donatorRepository
        )
        {        
        $this->donationRepository = $donationRepository;
        $this->donatorRepository = $donatorRepository;

        $this->module_title = Str::plural(class_basename($this->donationRepository->model()));

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

        $donation =$this->donationRepository->query()->orderBy('order','asc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donation,
        );
    }

    public function getList(){

        $donation =$this->donationRepository->query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donation,
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
            $donationObject = $this->donationRepository->make($data);

            $donationObjectArray = $donationObject->toArray();
            
            $donation = $this->donationRepository->create($donationObjectArray);

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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$donation->name.'(ID:'.$donation->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donation,
        );
    }

    public function show($id){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->donationRepository->findOrFail($id),
        );
    }

    public function edit($id){

        $donation = $this->donationRepository->findOrFail($id);

        $donation->installment_ids = json_decode($donation->installment_ids, true);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$donation->name.'(ID:'.$donation->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donation,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{
            $donation = $this->donationRepository->make($data);

            $updated = $this->donationRepository->update($donation->toArray(),$id);

            $updated_donation = $this->donationRepository->findOrFail($id);
            
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_donation->name.'(ID:'.$updated_donation->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_donation,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $donations = $this->donationRepository->findOrFail($id);
    
            $deleted = $this->donationRepository->delete($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$donations->name.', ID:'.$donations->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donations,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->donationRepository->trashed(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $donations= $this->donationRepository->restore($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$donations->name.", ID:".$donations->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donations,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $donations = $this->donationRepository->findTrash($id);
    
            $deleted = $this->donationRepository->purge($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$donations->name.', ID:'.$donations->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donations,
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
        
        $donators = $this->donatorRepository->pluck("donator_name","id");

        $options = array(
            'donators' => $donators,
        );

        return $options;
    }
}