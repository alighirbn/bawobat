<?php

namespace App\DataTables;

use App\Models\Income\Income;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IncomeDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'income.action')
            ->rawColumns(['action'])
            ->setRowId('id');
    }



    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Income $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Income $model): QueryBuilder
    {
        // Get the base query with relationships
        $query = $model->newQuery();
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('income-table')
            ->language([
                'sUrl' =>  url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            ->parameters([
                'dom' => 'B<"clear">lfrtip',
                'scrollX' => false,
                'buttons' => [
                    [
                        'extend'  => 'print',
                        'className'    => 'btn btn-outline-dark'
                    ],
                    /*[
                    'extend'  => 'reset',
                    'className'    => 'btn btn-outline-dark'
               ],
               [
                    'extend'  => 'reload',
                    'className'    => 'btn btn-outline-dark'
               ],
                [
                     'extend'  => 'export',
                     'className'    => 'btn btn-outline-dark',
                     'buttons' => [
                                       'csv',
                                       'excel',
                                       'pdf',
                                  ],
                ], */
                    'colvis'
                ]
            ])
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title(__('word.action'))
                ->addClass('text-center'),
            Column::make('id')->title(__('word.Income_id'))->class('text-center'),
            Column::make('Income_date')->title(__('word.Income_date'))->class('text-center'),

            Column::make('Income_contract_id')->title(__('word.contract_id'))->data('Income_contract_id')->class('text-center'),
            Column::make('contract_date')->title(__('word.contract_date'))->data('contract.contract_date')->name('contract.contract_date')->class('text-center'),
            Column::make('building_number')->title(__('word.building_number'))->data('contract.building.building_number')->name('contract.building.building_number')->class('text-center'),

            Column::make('customer_full_name')->title(__('word.customer_full_name'))->data('contract.customer.customer_full_name')->name('contract.customer.customer_full_name')->class('text-center'),
            Column::make('installment_name')->title(__('word.installment_name'))->data('contract_installment.installment.installment_name')->name('contract_installment.installment.installment_name')->class('text-center'),

            Column::make('Income_amount')->title(__('word.Income_amount'))->class('text-center'),
            Column::make('approved')
                ->title(__('word.approve_status'))
                ->class('text-center')
                ->orderable(false) // Disable sorting
                ->searchable(false), // Disable searching
            Column::make('cash_account')->title(__('word.account_name'))->data('cash_account.account_name')->name('cash_account.account_name')->class('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Income_' . date('YmdHis');
    }
}
