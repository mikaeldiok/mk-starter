<?php

namespace Modules\Fund\DataTables\Frontend\Donator;

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
            ->editColumn('amount',function($data){
                return 'Rp. '.number_format($data->amount ?? 0 , 0, ',', '.');
            })
            ->editColumn('donation_date', function ($data) {
                $module_name = $this->module_name;

                $formated_date = Carbon::parse($data->created_at)->format('d-m-Y');

                return $formated_date;
            })
            ->editColumn('created_at', function ($data) {
                $module_name = $this->module_name;

                $formated_date = Carbon::parse($data->created_at)->format('d-m-Y, H:i:s');

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
                ->where('donator_id', $user->donator->id);

        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $created_at = 3;
        return $this->builder()
                ->setTableId('donations-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->dom(config('mk-datatables.mk-dom-frontend-donators-home'))
                ->orderBy($created_at,'desc')
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
            Column::make('amount'),
            Column::make('donation_date'),
            Column::make('created_at')->title('Confirmed at'),
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
}