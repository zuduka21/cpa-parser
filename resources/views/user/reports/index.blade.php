@extends('admin.layouts.parser')

@section('pagejsplugins')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('/js/plugins/datatables/dataTables.bootstrap4.css')}}">
    {{--<link rel="stylesheet" href="{{asset('/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">--}}
    <link rel="stylesheet" href="{{asset('/js/plugins/select2/css/select2.min.css')}}">

    {{--calendar--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    {{--endcalendar--}}
@endsection

@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Report</h3>
            @if (Auth::user()->checkRole('admin') || Auth::user()['allow_export'] == true)
                <form action="{{route('user_export_allow_brands')}}" method="POST">
                    @csrf
                    <input id="export_allow_id" type="hidden" name="export_allow_id" value="">
                    <input id="export_allow_type" type="hidden" name="export_allow_type" value="">
                    <input id="export_first_date" type="hidden" name="first_date" value="">
                    <input id="export_group_id" type="hidden" name="group_id" value="">
                    <input id="export_last_date" type="hidden" name="last_date" value="">
                    <input id="export_tracker_id" type="hidden" name="tracker_id" value="">
                    <input id="export_brands_id" type="hidden" name="brands_id" value="">
                    <input id="export_creative_id" type="hidden" name="creative_id" value="">
                    <button type="submit" class="btn btn-alt-success">Export report</button>
                </form>
            @endif
        </div>

        <div class="block-content block-content-full mb-3 row">
            <div class="col-md-3 mt-md-3">
                <div class="form-group">
                    @if(!empty($allow))
                        <label for="{{$allowType}}_id">{{$allowType}}</label>
                        <select class="js-select2 form-control" id="{{$allowType}}_id" name="{{$allowType}}_id">
                            @foreach($allow as $item)
                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <input id="allow_type" type="hidden" name="allow_type" value="{{$allowType}}">
            </div>
            <div class="col-md-3 mt-md-3">
                <div class="form-group">
                    <label for="tracker_id">Trackers</label>
                    <select class="js-select2 form-control" id="tracker_id" name="id" disabled>
                        <option value="">choose a partner first</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-md-3">
                <div class="form-group">
                    <label for="brands_id">Brands</label>
                    <select class="js-select2 form-control" id="brands_id" name="id" disabled>
                        <option value="">choose a partner first</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-md-3">
                <div class="form-group">
                    <label for="group_id">Group by</label>
                    <select class="js-select2 form-control" id="group_id" name="id">
                        <option value="">All</option>
                        @foreach($groups as $group)
                            <option value="{{$group}}">{{$group}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-md-3">
                <label for="date">Date</label>
                <input type="text" id="date" class="form-control" name="partner_date" value="01-01-2019 - 01-15-2019"/>
            </div>
            <div class="col-md-3 mt-md-3">
                <div class="form-group">
                    <label for="creative_id">Creative</label>
                    <input type="text" id="creative_id" class="form-control" name="creative_id" value="">
                </div>
            </div>
            <div class="col-md-3 mt-md-5">
                <button onclick="search()" type="button" class="btn btn-alt-primary btn-block">Apply filters</button>
            </div>

            <div class="table-responsive">
                <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th class="align-middle text-center">Brand</th>
                        <th class="align-middle text-center">Tracker</th>
                        <th class="align-middle text-center">Creative</th>
                        <th class="align-middle text-center">Transaction date</th>
                        <th class="align-middle text-center">Views</th>
                        <th class="align-middle text-center">Clicks</th>
                        <th class="align-middle text-center">Signups</th>
                        <th class="align-middle text-center">FTDC</th>
                        <th class="align-middle text-center">Depositing Customers</th>
                        <th class="align-middle text-center">Total Deposits</th>
                        <th class="align-middle text-center">Active Customers</th>
                        <th class="align-middle text-center">Sportsbook NGR</th>
                        <th class="align-middle text-center">Casino NGR</th>
                        <th class="align-middle text-center">NGR</th>
                        <th class="align-middle text-center">CPA Qualified</th>
                        <th class="align-middle text-center">Revenue Share Profit</th>
                        <th class="align-middle text-center">CPA Profit</th>
                        <th class="align-middle text-center">Total Profit</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('pagescripts')
    <!-- Page JS Plugins -->
    <script src="{{asset('/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    {{--calendar--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    {{--endcalendar--}}
    <script>
        function search() {
            $.fn.dataTable.ext.errMode = 'none';
            var allow_type = $('#allow_type').val();
            var allow_id = $('#' + allow_type + '_id').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if (dateReg != null || dateReg != undefined) {
                var date = Array.from($('#date').val().match(regexp));
            }
            var partner_first_date = '';
            var partner_last_date = '';
            if (date !== undefined) {
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            var group_id = $('#group_id').val();
            var tracker_id = $('#tracker_id').val();
            var creative_id = $('#creative_id').val();
            var brands_id = $('#brands_id').val();
            if (brands_id) {
            } else {
                brands_id = $('#brands_id').find("option:first-child").val()
            }
            if (creative_id) {
            } else {
                creative_id = $('#creative_id').find("option:first-child").val()
            }
            if (tracker_id) {
            } else {
                tracker_id = $('#tracker_id').find("option:first-child").val()
            }
            if (group_id) {
            } else {
                group_id = $('#group_id').find("option:first-child").val()
            }

            $('#myTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('user_reports_get_reports') !!}',
                    "data": {
                        "allow_id": allow_id,
                        "brands_id": brands_id,
                        "allow_type": allow_type,
                        'creative_id': creative_id,
                        'tracker_id': tracker_id,
                        'group_id': group_id,
                        "partner_first_date": partner_first_date,
                        "partner_last_date": partner_last_date,
                    }
                },
                order: [3, "desc"],
                columns: columns(),
                initComplete: async function (settings, json) {
                    await total();
                    changeExportInputs();
                }
            });
        }

        function changeExportInputs() {
            $('#export_allow_type').val($('#allow_type').val());
            $('#export_first_date').val($('#partner_first_date').val());
            $('#export_last_date').val($('#partner_last_date').val());
            var allow_type = $('#allow_type').val();
            let element = $('#' + allow_type + '_id').val();
            if (element) {
                element = $('#' + allow_type + '_id').find("option:first-child").val()
            }
            $('#export_allow_id').val(element);
        }

        function columns() {
            return [
                {data: 'Brand', name: 'Brand'},
                {data: 'Tracker', name: 'Tracker'},
                {data: 'Creative', name: 'Creative'},
                {data: 'Transaction_date', name: 'Transaction_date'},
                {data: 'Views', name: 'Views'},
                {data: 'Clicks', name: 'Clicks'},
                {data: 'Signups', name: 'Signups'},
                {data: 'FTDC', name: 'FTDC'},
                {data: 'Depositing_Customers', name: 'Depositing_Customers'},
                {data: 'Total_Deposits', name: 'Total_Deposits'},
                {data: 'Active_Customers', name: 'Active_Customers'},
                {data: 'Sportsbook_NGR', name: 'Sportsbook_NGR'},
                {data: 'Casino_NGR', name: 'Casino_NGR'},
                {data: 'NGR', name: 'NGR'},
                {data: 'CPA_Qualified', name: 'CPA_Qualified'},
                {data: 'Revenue_Share_Profit', name: 'Revenue_Share_Profit'},
                {data: 'CPA_Profit', name: 'CPA_Profit'},
                {data: 'Total_Profit', name: 'Total_Profit'},
            ];
        }

        function total() {
            var allow_type = $('#allow_type').val();
            var allow_id = $('#' + allow_type + '_id').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if (dateReg != null || dateReg != undefined) {
                var date = Array.from($('#date').val().match(regexp));
            }
            var partner_first_date = '';
            var partner_last_date = '';
            if (date !== undefined) {
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            var group_id = $('#group_id').val();
            var tracker_id = $('#tracker_id').val();
            var creative_id = $('#creative_id').val();
            var brands_id = $('#brands_id').val();
            if (brands_id) {
            } else {
                brands_id = $('#brands_id').find("option:first-child").val()
            }
            if (creative_id) {
            } else {
                creative_id = $('#creative_id').find("option:first-child").val()
            }
            if (tracker_id) {
            } else {
                tracker_id = $('#tracker_id').find("option:first-child").val()
            }
            if (group_id) {
            } else {
                group_id = $('#group_id').find("option:first-child").val()
            }

            $.ajax({
                type: 'post',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('user_reports_get_total')}}',
                data: {
                    "allow_id": allow_id,
                    "allow_type": allow_type,
                    "brands_id": brands_id,
                    'creative_id': creative_id,
                    'tracker_id': tracker_id,
                    'group_id': group_id,
                    "partner_first_date": partner_first_date,
                    "partner_last_date": partner_last_date,
                },
                success: function (response) {
                    var answer = JSON.parse(response);
                    if (answer !== false) {
                        $('tbody').append('<tr>' +
                            '<td>' + answer['Brand'] + '</td>' +
                            '<td>' + answer['Tracker'] + '</td>' +
                            '<td>' + answer['Creative'] + '</td>' +
                            '<td>' + answer['Transaction_date'] + '</td>' +
                            '<td>' + answer['Views'] + '</td>' +
                            '<td>' + answer['Clicks'] + '</td>' +
                            '<td>' + answer['Signups'] + '</td>' +
                            '<td>' + answer['FTDC'] + '</td>' +
                            '<td>' + answer['Depositing_Customers'] + '</td>' +
                            '<td>' + answer['Total_Deposits'] + '</td>' +
                            '<td>' + answer['Active_Customers'] + '</td>' +
                            '<td>' + answer['Sportsbook_NGR'] + '</td>' +
                            '<td>' + answer['Casino_NGR'] + '</td>' +
                            '<td>' + answer['NGR'] + '</td>' +
                            '<td>' + answer['CPA_Qualified'] + '</td>' +
                            '<td>' + answer['Revenue_Share_Profit'] + '</td>' +
                            '<td>' + answer['CPA_Profit'] + '</td>' +
                            '<td>' + answer['Total_Profit'] + '</td>' +
                            '</tr>');
                    }
                }
            })
        }

        $(document).ready(async function () {
           await getTrackers();
           await getBrands();
           await changeExportInputs();

            var allow_type = $('#allow_type').val();
            var allow_id = $('#' + allow_type + '_id').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if (dateReg != null || dateReg != undefined) {
                var date = Array.from($('#date').val().match(regexp));
            }
            var partner_first_date = '';
            var partner_last_date = '';
            if (date !== undefined) {
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            var group_id = $('#group_id').val();
            var tracker_id = $('#tracker_id').val();
            var creative_id = $('#creative_id').val();
            var brands_id = $('#brands_id').val();
            if (brands_id) {
            } else {
                brands_id = $('#brands_id').find("option:first-child").val()
            }
            if (creative_id) {
            } else {
                creative_id = $('#creative_id').find("option:first-child").val()
            }
            if (tracker_id) {
            } else {
                tracker_id = $('#tracker_id').find("option:first-child").val()
            }
            if (group_id) {
            } else {
                group_id = $('#group_id').find("option:first-child").val()
            }
            $.fn.dataTable.ext.errMode = 'none';
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('user_reports_get_reports') !!}',
                    "data": {
                        "allow_id": allow_id,
                        "brands_id": brands_id,
                        'creative_id': creative_id,
                        'tracker_id': tracker_id,
                        'group_id': group_id,
                        "allow_type": allow_type,
                        "partner_first_date": partner_first_date,
                        "partner_last_date": partner_last_date,
                    }
                },
                order: [3, "desc"],
                columns: columns(),
                initComplete: async function (settings, json) {
                    await total();
                }
            });

            async function getBrands() {
                var allow_type = $('#allow_type').val();
                var allow_id = $('#' + allow_type + '_id').val();
                await $.ajax({
                    type: 'post',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('user_reports_get_brands')}}',
                    data: {allow_type: allow_type, allow_id: allow_id},
                    success: async function (response) {
                        var answer = JSON.parse(response);
                        if (answer !== false) {
                            $('#brands_id').empty();
                            $('#brands_id').append(new Option('All', ''));
                            answer.forEach(await function (element) {
                                if (element) {
                                    var re = /_/gi;
                                    var newstr = element['name'].toString().replace(re, ' ');
                                    $('#brands_id').append(new Option(newstr, element['id']));
                                } else {
                                }
                            });
                            await changeExportInputs();
                            $("#brands_id").attr("disabled", false);
                            return true;
                        } else {
                            $('#brands_id').empty();
                            $('#brands_id').append(new Option('choose a partner first', ''));
                            $("#brands_id").attr("disabled", true);
                            return false;
                        }
                    }
                });
            }

            async function getTrackers() {
                var allow_type = $('#allow_type').val();
                var allow_id = $('#' + allow_type + '_id').val();
                await $.ajax({
                    type: 'post',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('user_reports_get_trackers')}}',
                    data: {allow_type: allow_type, allow_id: allow_id},
                    success: async function (response) {
                        var answer = JSON.parse(response);
                        if (answer !== false) {
                            $('#tracker_id').empty();
                            $('#tracker_id').append(new Option('All', ''));
                            answer.forEach(await function (element) {
                                if (element) {
                                    var re = /_/gi;
                                    var newstr = element.toString().replace(re, ' ');
                                    $('#tracker_id').append(new Option(newstr, element));
                                } else {
                                }
                            });
                            await changeExportInputs();
                            $("#tracker_id").attr("disabled", false);
                            return true;
                        } else {
                            $('#tracker_id').empty();
                            $('#tracker_id').append(new Option('choose a partner first', ''));
                            $("#tracker_id").attr("disabled", true);
                            return false;
                        }
                    }
                });
            }

            $('#creative_id').change(async function () {
                $('#export_creative_id').val($('#creative_id').val());
            });
            $('#group_id').change(function () {
                $('#export_group_id').val($('#group_id').val());
            });
            $('#tracker_id').change(function () {
                $('#export_tracker_id').val($('#tracker_id').val());
            });
            $('#allow_type').change(function () {
                $('#export_allow_type').val($('#allow_type').val());
                getTrackers();
                getBrands();
            });
            $('#date').change(function () {
                var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
                var date = Array.from($('#date').val().match(regexp));
                var partner_first_date = date[1];
                var partner_last_date = date[4];
                if (date !== undefined) {
                    $('#export_last_date').val(partner_last_date);
                    $('#export_first_date').val(partner_first_date);
                }
            });
            $('#brands_id').change(async function () {
                $('#export_brands_id').val($('#brands_id').val());
            });

            var allow_type = $('#allow_type').val();

            $('#' + allow_type + '_id').change(function () {
                $('#export_allow_id').val($('#' + allow_type + '_id').val());
                getTrackers();
                getBrands();
            });
        })
    </script>
    <!-- Page JS Code -->
    <script src="{{asset('/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Page JS Helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins) -->

    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function () {
            Codebase.helpers(['datepicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider', 'tags-inputs', 'select2']);
        });</script>
    {{--<script src="{{asset('/js/pages/be_tables_datatables.min.js')}}"></script>--}}
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
