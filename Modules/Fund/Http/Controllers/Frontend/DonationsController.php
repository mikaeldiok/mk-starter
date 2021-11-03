<?php

namespace Modules\Fund\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Modules\Fund\Services\DonationService;
use Spatie\Activitylog\Models\Activity;

class DonationsController extends Controller
{
    protected $donationService;

    public function __construct(DonationService $donationService)
    {

        // Page Title
        $this->module_title = trans('menu.fund.donations');

        // module name
        $this->module_name = 'donations';

        // directory path of the module
        $this->module_path = 'donations';

        // module icon
        $this->module_icon = 'fas fa-wallet';

        // module model name, path
        $this->module_model = "Modules\Donation\Entities\Donation";

        $this->donationService = $donationService;
    }

    /**
     * Go to donation homepage
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index()
    {
        return view('fund::frontend.donations.index');
    }
}
