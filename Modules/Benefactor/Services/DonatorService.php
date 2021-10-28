<?php

namespace Modules\Benefactor\Services;

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


use Modules\Benefactor\Imports\DonatorsImport;

class DonatorService{

    protected $donatorRepository;

    public function __construct(
        DonatorRepository $donatorRepository
        )
        {        
        $this->donatorRepository = $donatorRepository;

        $this->module_title = Str::plural(class_basename($this->donatorRepository->model()));

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

        $donator =$this->donatorRepository->query()->orderBy('order','asc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donator,
        );
    }

    public function getList(){

        $donator =$this->donatorRepository->query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donator,
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
            $donatorObject = $this->donatorRepository->make($data);

            $bank = preg_split('/-/',$data['donator_bank_name']);

            $donatorObject->donator_bank_code = $bank[0];
            $donatorObject->donator_bank_name = $bank[1];

            $donatorObjectArray = $donatorObject->toArray();
            $donatorObjectArray['password'] = Hash::make($data['password']);

            $donator = $this->donatorRepository->create($donatorObjectArray);

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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$donator->name.'(ID:'.$donator->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donator,
        );
    }

    public function show($id){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->donatorRepository->findOrFail($id),
        );
    }

    public function edit($id){

        $donator = $this->donatorRepository->findOrFail($id);

        $donator->installment_ids = json_decode($donator->installment_ids, true);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$donator->name.'(ID:'.$donator->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donator,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{
            $donator = $this->donatorRepository->make($data);
            
            $donatorArray = $donator->toArray();
            $donatorArray['password'] = Hash::make($data['password']);

            $updated = $this->donatorRepository->update($donatorArray,$id);

            $updated_donator = $this->donatorRepository->findOrFail($id);
            
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_donator->name.'(ID:'.$updated_donator->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_donator,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $donators = $this->donatorRepository->findOrFail($id);
    
            $deleted = $this->donatorRepository->delete($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$donators->name.', ID:'.$donators->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donators,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->donatorRepository->trashed(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $donators= $this->donatorRepository->restore($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$donators->name.", ID:".$donators->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donators,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $donators = $this->donatorRepository->findTrash($id);
    
            $deleted = $this->donatorRepository->purge($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$donators->name.', ID:'.$donators->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donators,
        );
    }

    public function import(Request $request){
                 
        $import = Excel::import(new DonatorsImport, $request->file('data_file'));

        
    
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

        $options = array(
            'banks' => $banks,
            'bank_names' => $bank_names
        );

        return $options;
    }
}