<?php

namespace App\DataTables;

use App\Models\Account\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
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
            ->addColumn('action', 'transaction.action')
            ->editColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->date)->format('Y-m-d'); // Format start_date
            })
            ->editColumn('id', function ($row) {
                return $row->period->name . '-' . $row->id; // Assuming 'period' is a relationship
            })
            ->addColumn('debit', function ($row) {
                return number_format($row->entries->where('debit_credit', 'debit')->sum('amount'), 0);
            })
            ->addColumn('credit', function ($row) {
                return number_format($row->entries->where('debit_credit', 'credit')->sum('amount'), 0);
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model): QueryBuilder
    {
        // Get the base query with relationships
        $query = $model->newQuery()->with(['period']);

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
            ->setTableId('transaction-table')
            ->language([
                'sUrl' =>  url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2, 'desc')
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


            Column::make('id')->title(__('word.id'))->class('text-center'),
            Column::make('date')->title(__('word.date'))->class('text-center'),

            Column::make('description')->title(__('word.description'))->class('text-center'),
            Column::make('debit')->title(__('word.debit'))->class('text-center'), // Debit column
            Column::make('credit')->title(__('word.credit'))->class('text-center'), // Credit column

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
