<?php

namespace Modules\Benefactor\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\Benefactor\Services\DonatorService;
use Spatie\Activitylog\Models\Activity;
use Modules\Fund\DataTables\Frontend\Donator\DonationsDataTable;

class DonatorsController extends Controller
{
    protected $donatorService;

    public function __construct(DonatorService $donatorService)
    {
        $this->middleware('auth:donator');

        // Page Title
        $this->module_title = trans('menu.benefactor.donators');

        // module name
        $this->module_name = 'donators';

        // directory path of the module
        $this->module_path = 'donators';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Donator\Entities\Donator";

        $this->donatorService = $donatorService;
    }

    /**
     * Go to donator homepage
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index(DonationsDataTable $dataTable)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $donators = $this->donatorService->show(Auth::user()->id);

        $$module_name_singular = $donators->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return $dataTable->render(
            "benefactor::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'driver')
        );
    }
}
