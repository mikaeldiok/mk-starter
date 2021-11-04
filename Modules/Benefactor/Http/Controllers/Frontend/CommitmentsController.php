<?php

namespace Modules\Benefactor\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\Benefactor\Services\CommitmentService;
use Modules\Benefactor\Http\Requests\Frontend\CommitmentsRequest;
use Spatie\Activitylog\Models\Activity;

class CommitmentsController extends Controller
{
    protected $commitmentService;

    public function __construct(CommitmentService $commitmentService)
    {
        // Page Title
        $this->module_title = trans('menu.benefactor.commitments');

        // module name
        $this->module_name = 'commitments';

        // directory path of the module
        $this->module_path = 'commitments';

        // module icon
        $this->module_icon = 'fas fa-handshake';

        // module model name, path
        $this->module_model = "Modules\Commitment\Entities\Commitment";

        $this->commitmentService = $commitmentService;
    }

    /**
     * Go to commitment homepage
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $commitments = $this->commitmentService->show(Auth::user()->id);

        $$module_name_singular = $commitments->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        $activities = Activity::where('subject_type', '=', $module_model)
            ->where('log_name', '=', $module_name)
            ->where('subject_id', '=', Auth::user()->id)
            ->latest()
            ->paginate();

        return view(
            "benefactor::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'activities','driver')
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(CommitmentsRequest $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $commitments = $this->commitmentService->store($request);

        $$module_name_singular = $commitments;

        return redirect("/");
    }
}
