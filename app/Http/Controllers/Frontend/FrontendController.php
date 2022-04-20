<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Modules\Fund\DataTables\Frontend\Home\DonationsDataTable;
use Modules\Benefactor\Services\DonatorService;

class FrontendController extends Controller
{
    protected $donatorService;

    public function __construct(DonatorService $donatorService)
    {
        $this->donatorService = $donatorService;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DonationsDataTable $dataTable)
    {
        $body_class = '';

        $donation_model = "Modules\Fund\Entities\Donation";

        $donations = $donation_model::paginate();

        $options = $this->donatorService->create();

        $options_data = $options->data;

        $banks = $options_data['banks'];
        $donator_types = $options_data['donator_types'];

        return $dataTable->render("frontend.index",
            compact('body_class','donator_types','banks')
        );

    }

    /**
     * Privacy Policy Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacy()
    {
        $body_class = '';

        return view('frontend.privacy', compact('body_class'));
    }

    /**
     * Terms & Conditions Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function terms()
    {
        $body_class = '';

        return view('frontend.terms', compact('body_class'));
    }
}
