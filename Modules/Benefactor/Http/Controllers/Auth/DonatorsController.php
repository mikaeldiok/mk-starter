<?php

namespace Modules\Benefactor\Http\Controllers\Auth;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController as DefaultLoginController;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Modules\Benefactor\Services\DonatorService;
use Modules\Benefactor\Http\Requests\Frontend\DonatorsRequest;
use Modules\Benefactor\DataTables\DonatorsDataTable;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class DonatorsController extends DefaultLoginController
{
    protected $redirectTo = '/donators/home';
    protected $donatorService;

    public function __construct(DonatorService $donatorService)
    {
        $this->middleware('guest:donator')->except('logout');

        // Page Title
        $this->module_title = trans('menu.benefactor.donators');

        // module name
        $this->module_name = 'donators';

        // directory path of the module
        $this->module_path = 'donators';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Benefactor\Entities\Donator";

        $this->donatorService = $donatorService;
    }
    
    public function showLoginForm()
    {
        return view('benefactor::auth.login');
    }

    public function showRegisterForm()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        $options = $this->donatorService->create();

        $options_data = $options->data;

        $banks = $options_data['banks'];
        $donator_types = $options_data['donator_types'];

        return view(
            'benefactor::auth.register',
            compact('module_title', 'module_name', 'module_icon', 'module_action', 'module_name_singular','banks', 'donator_types')
        );
    }

    public function register(DonatorsRequest $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $donators = $this->donatorService->store($request);

        $$module_name_singular = $donators->data;

        if(!$donators->error){
            Flash::success('<i class="fas fa-check"></i>  Selamat anda sudah terdaftar, silakan langsung masuk dengan menggunakan email dan password anda!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error saat ".$module_action." '".Str::singular($module_title)."'")->important();
        }

        return redirect("$module_name/login");
    }

    public function username()
    {
        return 'donator_email';
    }

    protected function guard()
    {
        return Auth::guard('donator');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
       
        Auth::guard('donator')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
