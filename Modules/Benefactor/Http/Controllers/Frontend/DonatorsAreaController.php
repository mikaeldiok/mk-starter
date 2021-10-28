<?php

namespace Modules\Benefactor\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Modules\Benefactor\Services\DonatorService;
use Spatie\Activitylog\Models\Activity;

class DonatorsAreaController extends Controller
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
    public function index()
    {
        return view('benefactor::frontend.donators-area.index');
    }
}
