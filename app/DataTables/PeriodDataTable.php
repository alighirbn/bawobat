<?php

namespace App\DataTables;

use App\Models\Account\Period;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PeriodDataTable extends DataTable
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
            ->addColumn('action', 'period.action')
            ->rawColumns(['action'])
            ->setRowId('id')
            ->editColumn('start_date', function ($period) {
                return \Carbon\Carbon::parse($period->start_date)->format('Y-m-d'); // Format start_date
            })
            ->editColumn('end_date', function ($period) {
                return \Carbon\Carbon::parse($period->end_date)->format('Y-m-d'); // Format end_date
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Period $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Period $model): QueryBuilder
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
            ->setTableId('period-table')
            ->language([
                'sUrl' => url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
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
                        'className' => 'btn btn-outline-dark'
                    ],
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
            Column::make('name')->title(__('word.name'))->class('text-center'),
            Column::make('start_date')->title(__('word.start_date'))->class('text-center'),
            Column::make('end_date')->title(__('word.end_date'))->class('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'period_' . date('YmdHis');
    }
}
