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
use Modules\Benefactor\Events\DonatorRegistered;

use App\Events\Backend\UserCreated;
use App\Models\User;

class DonatorService{

    protected $donatorRepository;

    public function __construct(
        DonatorRepository $donatorRepository
        )
        {        
        $this->donatorRepository = $donatorRepository;

        $this->module_title = Str::plural(class_basename($this->donatorRepository->model()));

        if(Auth::check()){
            $this->username = Auth::user()->name;
            $this->userid = Auth::user()->id;
        }else{
            $this->username = "Guest Registering";
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

            $user = $this->createNormalUser($request);
            
            $donatorObject = $this->donatorRepository->make($data);

            $bank = preg_split('/-/',$data['donator_bank_name']);

            $donatorObject->user_id = $user->id;
            $donatorObject->donator_bank_code = $bank[0];
            $donatorObject->donator_bank_name = $bank[1];

            $donatorObjectArray = $donatorObject->toArray();

            $donator = $this->donatorRepository->create($donatorObjectArray);

        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        event(new DonatorRegistered($donator));

        Log::info(label_case($this->module_title.' '.__function__)." | '".$donator->name.'(ID:'.$donator->id.") ' by User:".$this->username.'(ID:'.$this->userid.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $donator,
            'user'=> $user,
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

            $updated = $this->donatorRepository->update($donator->toArray(),$id);

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

        $donator_types = [
            'institusi'     => 'Institusi',
            'perorangan'     => 'Perorangan',
        ];

        $options = array(
            'banks'         => $banks,
            'bank_names'    => $bank_names,
            'donator_types' => $donator_types,
        );

        return $options;
    }

    public function createNormalUser($request){
        
        DB::beginTransaction();

        try{
            if(!$request->is_register){
                $request->confirmed = 1;
            }
            $data_array = $request->except('_token', 'roles', 'permissions', 'password_confirmation');
            $data_array['name'] = $request->first_name.' '.$request->last_name;
            $data_array['password'] = Hash::make($request->password);
    
            if ($request->confirmed == 1) {
                $data_array = Arr::add($data_array, 'email_verified_at', Carbon::now());
            } else {
                $data_array = Arr::add($data_array, 'email_verified_at', null);
            }
    
            $user = User::create($data_array);
    
            $roles = ["user"];
            $permissions = $request['permissions'];
    
            // Sync Roles
            if (isset($roles)) {
                $user->syncRoles($roles);
            } else {
                $roles = [];
                $user->syncRoles($roles);
            }
    
            // Sync Permissions
            if (isset($permissions)) {
                $user->syncPermissions($permissions);
            } else {
                $permissions = [];
                $user->syncPermissions($permissions);
            }
    
            // Username
            $id = $user->id;
            $username = config('app.initial_username') + $id;
            $user->username = $username;
            $user->save();

            if(!$request->is_register){
                event(new UserCreated($user));
            }

        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return null;
        }

        DB::commit();
    
        return $user;
    }
}