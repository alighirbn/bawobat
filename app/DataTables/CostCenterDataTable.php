<?php

namespace App\DataTables;

use App\Models\Account\CostCenter;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CostCenterDataTable extends DataTable
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
            ->addColumn('action', 'costcenter.action')
            ->rawColumns(['action'])
            ->setRowId('id');
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\costcenter $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CostCenter $model): QueryBuilder
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
            ->setTableId('costcenter-table')
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
            Column::make('code')->title(__('word.code'))->class('text-center'),
            Column::make('name')->title(__('word.name'))->class('text-center'),
            Column::make('description')->title(__('word.description'))->class('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'costcenter_' . date('YmdHis');
    }
}
