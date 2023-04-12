@extends('admin.layouts.parser')

@section('pagejsplugins')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('/js/plugins/datatables/dataTables.bootstrap4.css')}}">
    {{--calendar--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    {{--endcalendar--}}
@endsection

@section('content')
    <!-- Dynamic Table Full -->
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Logs
            </h3>
            <input type="hidden" id="log_id" name="log_id" value="user">
            <input type="hidden" id="table_number" name="table_number" value="1">
            <div class="col-md-4">
                <input type="text" id="date" class="form-control w-100" name="partner_date" value="01-01-2019 - 01-15-2019"/>
{{--                <div class="input-daterange input-group ml-2" data-date-format="yyyy-mm-dd" data-week-start="1"--}}
{{--                     data-autoclose="true" data-today-highlight="true">--}}
{{--                    <input type="text" class="form-control" id="first_date" name="first_date"--}}
{{--                           placeholder="From" data-week-start="1" data-autoclose="true"--}}
{{--                           data-today-highlight="true">--}}
{{--                    <div class="input-group-prepend input-group-append">--}}
{{--                        <span class="input-group-text font-w600">to</span>--}}
{{--                    </div>--}}
{{--                    <input type="text" class="form-control" id="last_date" name="last_date"--}}
{{--                           placeholder="To"--}}
{{--                           data-week-start="1" data-autoclose="true" data-today-highlight="true">--}}
{{--                </div>--}}
            </div>
            <button onclick="search()" type="submit" class=" ml-2 btn btn-alt-primary">Apply filters</button>
        </div>

        <!-- Block Tabs Default Style -->
        <div class="block">
            <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a onclick="changeInputs(1,'user')" class="nav-link active" href="#btabs-static-users">Users</a>
                </li>
                <li class="nav-item">
                    <a onclick="changeInputs(2,'export')" class="nav-link" href="#btabs-static-export">Exports</a>
                </li>
                <li class="nav-item">
                    <a onclick="changeInputs(3,'parsers')" class="nav-link" href="#btabs-static-parsers">Parsers</a>
                </li>
                <li class="nav-item">
                    <a onclick="changeInputs(4,'country')" class="nav-link" href="#btabs-static-country">Countries</a>
                </li>
                <li class="nav-item">
                    <a onclick="changeInputs(5,'partner')" class="nav-link" href="#btabs-static-partners">Partners</a>
                </li>
                <li class="nav-item ml-auto">
                    <a class="nav-link" href="#btabs-static-settings">
                        <i class="si si-settings"></i>
                    </a>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="btabs-static-users" role="tabpanel">
                    <h4 class="font-w400">Users Log</h4>
                    <div class="block-content block-content-full">
                        <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable_1" style="width:100%">
                            <thead>
                            <tr>
                                <th>header</th>
                                <th>datetime</th>
                                <th>user</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="btabs-static-export" role="tabpanel">
                    <h4 class="font-w400">Export Log</h4>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable_2" style="width:100%">
                        <thead>
                        <tr>
                            <th>header</th>
                            <th>datetime</th>
                            <th>user</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="btabs-static-parsers" role="tabpanel">
                    <h4 class="font-w400">Parsers Log</h4>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable_3" style="width:100%">
                        <thead>
                        <tr>
                            <th>header</th>
                            <th>datetime</th>
                            <th>user</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="btabs-static-country" role="tabpanel">
                    <h4 class="font-w400">Country Log</h4>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable_4" style="width:100%">
                        <thead>
                        <tr>
                            <th>header</th>
                            <th>datetime</th>
                            <th>user</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="btabs-static-partners" role="tabpanel">
                    <h4 class="font-w400">Partners Log</h4>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable_5" style="width:100%">
                        <thead>
                        <tr>
                            <th>header</th>
                            <th>datetime</th>
                            <th>user</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="btabs-static-settings" role="tabpanel">
                    <h4 class="font-w400">Settings Content</h4>
                </div>
            </div>
        </div>
        <!-- END Block Tabs Default Style -->
    </div>
    <!-- END Dynamic Table Full -->
@endsection

@section('pagescripts')
    <!-- Page JS Plugins -->
    <script src="{{asset('/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

    {{--calendar--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    {{--endcalendar--}}

    <script>
        function changeInputs(tableNumber,logName) {
            $('#log_id').val(logName);
            $('#table_number').val(tableNumber);
            var log_id = $('#log_id').val();
            var table_number = $('#table_number').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if(dateReg != null || dateReg != undefined){
                var date = Array.from($('#date').val().match(regexp));
            }
            var first_date = '';
            var last_date = '';
            if(date !== undefined){
                first_date = date[1];
                last_date = date[4];
            }
            $.fn.dataTable.ext.errMode = 'none';
            $('#myTable_'+table_number).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('admin_logs_get_all') !!}',
                    "data": {
                        "log_id": log_id,
                        "first_date": first_date,
                        "last_date": last_date,
                    }
                },
                columns: columns(),
                initComplete: function (settings, json) {
                }
            });
        }

        function search() {
            $.fn.dataTable.ext.errMode = 'none';
            var log_id = $('#log_id').val();
            var table_number = $('#table_number').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if(dateReg != null || dateReg != undefined){
                var date = Array.from($('#date').val().match(regexp));
            }
            var first_date = '';
            var last_date = '';
            if(date !== undefined){
                first_date = date[1];
                last_date = date[4];
            }

            $('#myTable_'+table_number).DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('admin_logs_get_all') !!}',
                    "data": {
                        "log_id": log_id,
                        "first_date": first_date,
                        "last_date": last_date,
                    }
                },
                columns: columns(),
                initComplete: function (settings, json) {
                }
            });
        }

        $(document).ready(function () {
            var log_id = $('#log_id').val();
            var table_number = $('#table_number').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if(dateReg != null || dateReg != undefined){
                var date = Array.from($('#date').val().match(regexp));
            }
            var first_date = '';
            var last_date = '';
            if(date !== undefined){
                first_date = date[1];
                last_date = date[4];
            }
            $.fn.dataTable.ext.errMode = 'none';
            $('#myTable_'+table_number).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('admin_logs_get_all') !!}',
                    "data": {
                        "log_id": log_id,
                        "first_date": first_date,
                        "last_date": last_date,
                    }
                },
                columns: columns(),
                initComplete: function (settings, json) {
                }
            });
            search();
        });

        function columns() {
            return [
                {data: 'header', name: 'header'},
                {data: 'datetime', name: 'datetime'},
                {data: 'user', name: 'user'}
            ];
        }
    </script>
    {{--Date picker--}}
    <script>
        $(function () {
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#date span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            }

            $('#date').daterangepicker({
                startDate: start,
                endDate: end,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
            cb(start, end);
        });
    </script>
@endsection
