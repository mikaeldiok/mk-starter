<?php

namespace Modules\Fund\DataTables\Frontend\Home;

use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Modules\Fund\Repositories\DonationRepository;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DonationsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function __construct(DonationRepository $donationRepository)
    {
        $this->module_name = 'donations';

        $this->donationRepository = $donationRepository;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('donator.donator_bank_account',function($data){

                return  $this->hideStringStatic($data->donator->donator_bank_account);
            })
            ->editColumn('amount',function($data){
                return 'Rp. '.number_format($data->amount ?? 0 , 0, ',', '.');
            })
            ->editColumn('donation_date', function ($data) {
                $module_name = $this->module_name;

                $formated_date = Carbon::parse($data->donation_date)->format('d-M-Y');

                return $formated_date;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Donation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $user = auth()->user();
        $data = $this->donationRepository->query()
                ->select('donations.*')
                ->with(['donator'])
                ->limit(100);

        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $id = 0;
        return $this->builder()
                ->setTableId('donations-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->dom(config('mk-datatables.mk-dom-frontend-donations'))
                ->buttons(
                    Button::make('export'),
                    Button::make('print'),
                    Button::make('reset')->className('rounded-right'),
                    Button::make('colvis')->text('Kolom')->className('m-2 rounded btn-info'),
                )->parameters([
                    'paging' => true,
                    'searching' => true,
                    'info' => true,
                    'responsive' => true,
                    'autoWidth' => false,
                    'searchDelay' => 350,
                ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->hidden(),
            Column::make('donation_date')->title("Tanggal"),
            Column::make('amount'),
            Column::make('donator.donator_name')->hidden()->title("Donatur"),
            Column::make('donator.donator_bank_name')->hidden()->title("Bank"),
            Column::make('donator.donator_bank_account')->title("Rekening"),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Donations_' . date('YmdHis');
    }

    protected function hideStringStatic($string, $start_mod = 1, $length_mod = 1){
        $length = strlen($string) - floor(strlen($string) / 2) - $length_mod;
        $start = floor($length / 2) + $start_mod;
        $replacement = str_repeat('*', $length);
        return substr_replace($string, $replacement, $start, $length);
    }


    /**
     * Dynamically hide string according to length.
     *
     * @return string
     */
    protected function hideStringDynamic($string){
        $length = strlen($string) - floor(strlen($string) / 2);
        $start = floor($length / 2);
        $replacement = str_repeat('*', $length);
        return substr_replace($string, $replacement, $start, $length);
    }
}