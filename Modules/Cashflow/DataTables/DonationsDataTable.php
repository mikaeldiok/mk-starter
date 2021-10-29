<?php

namespace Modules\Cashflow\DataTables;

use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Modules\Cashflow\Repositories\DonationRepository;
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
    public function __construct(DonationRepository $DonationRepository)
    {
        $this->module_name = 'Donations';

        $this->DonationRepository = $DonationRepository;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                $module_name = $this->module_name;

                return view('backend.includes.action_column_admin', compact('module_name', 'data'));
            })
            ->editColumn('updated_at', function ($data) {
                $module_name = $this->module_name;

                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('LLLL');
                }
            })
            ->editColumn('created_at', function ($data) {
                $module_name = $this->module_name;

                $formated_date = Carbon::parse($data->created_at)->format('d-m-Y, H:i:s');

                return $formated_date;
            })
            ->rawColumns(['name', 'action']);
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
        $data = $this->DonationRepository->query();

        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $created_at = 8;
        return $this->builder()
                ->setTableId('Donations-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->dom(config('mk-datatables.mk-dom'))
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
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
            Column::make('Donation_name'),
            Column::make('Donation_email'),
            Column::make('Donation_phone'),
            Column::make('Donation_type')->hidden(),
            Column::make('Donation_bank_code')->hidden(),
            Column::make('Donation_bank_name'),
            Column::make('Donation_bank_account'),
            Column::make('created_at'),
            Column::make('updated_at')->hidden(),
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