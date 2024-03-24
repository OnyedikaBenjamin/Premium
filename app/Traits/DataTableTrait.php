<?php


namespace App\Traits;

use Yajra\DataTables\Services\DataTable;

trait DataTableTrait {

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters($this->getBuilderParameters());
    }


    public function getBuilderParameters()
    {
        return [
            'lengthMenu'   => [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
            "sDom"         => "R"."<'row align-items-center pt-3 px-4'<'col-md-2' l><'col-md-4' B><'col-md-6' fr>>"."<'row' <'col-md-12 table-responsive' t>>" ."<'row p-4'<'col-sm-6'i><'col-sm-6 text-sm-center'p>>",
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fa fa-print"></i> Print',
                    'className' => 'btn btn-primary btn-sm',
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fa fa-file"></i> CSV',
                    'className' => 'btn btn-primary btn-sm',
                ]
            ],
            'drawCallback' => "function () {
                $('.dataTables_paginate > .pagination').addClass('justify-content-end mb-0');
            }",
            'language' => [
                'search' => '',
                'searchPlaceholder' => 'Search',
            ],
            'initComplete' => "function () {
               /* $('#dataTableBuilder_wrapper thead').addClass('thead-light'); */
                $('#dataTableBuilder_wrapper .dt-buttons button').removeClass('btn-secondary');
                this.api().columns().every(function () {
                    /*var column = this;
                    var input = document.createElement(\"input\");
                    input.className = \"form-control h-3\";
                    $(input).appendTo($(column.footer()).empty())
                    .on('keyup change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? val : '', false, false, true).draw();
                    });*/

                    /*$('#dataTableBuilder_wrapper thead').append($('#dataTableBuilder_wrapper tfoot tr'));*/
                });
            },
            createdRow: (row, data, dataIndex, cells) => {
                if(data.deleted_at !== null && data.deleted_at !== undefined && data.deleted_at !== ''){
                    $(row).addClass('toggle-mode')
                    if ($('body').hasClass('dark')) {
                        $(row).addClass('dark-mode-trashed')
                        $(row).css('background-color','hsla(0,0%,100%,.08)')
                    }else{
                        $('body').addClass('toggle-light-mode')
                        $(row).addClass('light-mode-trashed')
                        $(row).css('background-color', 'rgb(229 234 239)')
                    }
                }
            }"
        ];
    }
}
