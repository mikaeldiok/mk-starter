<?php

namespace Modules\Benefactor\Services;

use Modules\Benefactor\Repositories\CommitmentRepository;
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


use Modules\Benefactor\Imports\CommitmentsImport;

class CommitmentService{

    protected $commitmentRepository;
    protected $donatorRepository;

    public function __construct(
        CommitmentRepository $commitmentRepository,
        DonatorRepository $donatorRepository
        )
        {        
        $this->commitmentRepository = $commitmentRepository;
        $this->donatorRepository = $donatorRepository;

        $this->module_title = Str::plural(class_basename($this->commitmentRepository->model()));

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

        $commitment =$this->commitmentRepository->query()->orderBy('order','asc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $commitment,
        );
    }

    public function getList(){

        $commitment =$this->commitmentRepository->query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $commitment,
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
            $commitmentObject = $this->commitmentRepository->make($data);

            $commitmentObjectArray = $commitmentObject->toArray();

            $commitment = $this->commitmentRepository->create($commitmentObjectArray);

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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$commitment->name.'(ID:'.$commitment->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $commitment,
        );
    }

    public function show($id){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->commitmentRepository->findOrFail($id),
        );
    }

    public function edit($id){

        $commitment = $this->commitmentRepository->findOrFail($id);


        Log::info(label_case($this->module_title.' '.__function__)." | '".$commitment->name.'(ID:'.$commitment->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $commitment,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{
            $commitment = $this->commitmentRepository->make($data);

            $updated = $this->commitmentRepository->update($commitment->toArray(),$id);

            $updated_commitment = $this->commitmentRepository->findOrFail($id);
            
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_commitment->name.'(ID:'.$updated_commitment->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_commitment,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $commitments = $this->commitmentRepository->findOrFail($id);
    
            $deleted = $this->commitmentRepository->delete($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$commitments->name.', ID:'.$commitments->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $commitments,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->commitmentRepository->trashed(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $commitments= $this->commitmentRepository->restore($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$commitments->name.", ID:".$commitments->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $commitments,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $commitments = $this->commitmentRepository->findTrash($id);
    
            $deleted = $this->commitmentRepository->purge($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$commitments->name.', ID:'.$commitments->id." ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $commitments,
        );
    }

    public function import(Request $request){
                 
        $import = Excel::import(new CommitmentsImport, $request->file('data_file'));

        
    
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $import,
        );
    }

    public function prepareOptions(){
        
        $donators = $this->donatorRepository->getDonatorsWithUser()->pluck('user.name','id');

        $options = array(
            'donators' => $donators,
        );

        return $options;
    }
}