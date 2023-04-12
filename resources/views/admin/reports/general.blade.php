@extends('admin.layouts.parser')

@section('pagejsplugins')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('/js/plugins/datatables/dataTables.bootstrap4.css')}}">
    {{--    <link rel="stylesheet" href="{{asset('/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">--}}
    <link rel="stylesheet" href="{{asset('/js/plugins/select2/css/select2.min.css')}}">

    {{--calendar--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    {{--endcalendar--}}
@endsection

@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">General report</h3>
            <form action="{{route('admin_export_partner_general')}}" method="POST">
                @csrf
                <input id="export_id" type="hidden" name="id" value="">
                <input id="export_first_date" type="hidden" name="first_date" value="">
                <input id="export_last_date" type="hidden" name="last_date" value="">
                <input id="export_group_id" type="hidden" name="group_id" value="">
                <input id="export_tracker_id" type="hidden" name="tracker_id" value="">
                <input id="export_brands_id" type="hidden" name="brands_id" value="">
                <input id="export_creative_id" type="hidden" name="creative_id" value="">
                <button type="submit" class="btn btn-alt-success"><i class="fa fa-download"></i> Export report</button>
            </form>
        </div>
        <div class="block-content block-content-full mb-3 row">
            <div class="col-md-3 mt-md-3">
                <div class="form-group">
                    <label for="partner_id">Partners</label>
                    <select class="js-select2 form-control" id="partner_id" name="id">
                        @foreach($partners as $partner)
                            <option value="{{$partner['id']}}">{{$partner['name']}}</option>
                        @endforeach
                    </select>
                </div>
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
            <div class="col-md-3 offset-md-3 mt-md-5">
                <button onclick="search()" type="button" class="btn btn-alt-primary btn-block">Apply filters</button>
            </div>
            </form>

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
                <div id="marge_count" class="col-md-3 mt-md-3" style="display: none">
                    <div class="form-group">
                        <label id="marge_count_name" for="creative_id"></label>
                        <p id="marge_count_count"></p>
                    </div>
                </div>
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
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if(dateReg != null || dateReg != undefined){
                var date = Array.from($('#date').val().match(regexp));
            }
            var partner_first_date = '';
            var partner_last_date = '';
            if(date !== undefined){
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            $.fn.dataTable.ext.errMode = 'none';
            var partner_id = $('#partner_id').val();
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
            if (partner_id) {
            } else {
                partner_id = $('#partner_id').find("option:first-child").val()
            }
            $('#myTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('admin_reports_get_general_reports') !!}',
                    "data": {
                        "partner_id": partner_id,
                        "brands_id": brands_id,
                        "creative_id": creative_id,
                        "group_id": group_id,
                        "tracker_id": tracker_id,
                        "partner_first_date": partner_first_date,
                        "partner_last_date": partner_last_date,
                    }
                },
                order: [3, "desc"],
                columns: columns(),
                initComplete: async function (settings, json) {
                    await total();
                    await getMargeCount();
                    changeExportInputs();
                }
            });
        }

        async function getMargeCount(){
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var dateReg = $('#date').val().match(regexp);
            if(dateReg != null || dateReg != undefined){
                var date = Array.from($('#date').val().match(regexp));
            }
            var partner_first_date = '';
            var partner_last_date = '';
            if(date !== undefined){
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            var group_id = $('#group_id').val();
            var partner_id = $('#partner_id').val();
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
            if (partner_id) {
            } else {
                partner_id = $('#partner_id').find("option:first-child").val()
            }
            if (tracker_id) {
            } else {
                tracker_id = $('#tracker_id').find("option:first-child").val()
            }
            if (group_id) {
            } else {
                group_id = $('#group_id').find("option:first-child").val()
            }
            await $.ajax({
                type: 'post',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('get_marge_count')}}',
                data: {
                    "partner_id": partner_id,
                    "brands_id": brands_id,
                    "creative_id": creative_id,
                    "group_id": group_id,
                    "tracker_id": tracker_id,
                    "partner_first_date": partner_first_date,
                    "partner_last_date": partner_last_date,},
                success: async function (response) {
                    var answer = JSON.parse(response);
                    console.log(answer);
                    if(answer !== false){
                        var re = /_/gi;
                        var newstr = answer['name'].toString().replace(re, ' ');
                        $('#marge_count').show();
                        $('#marge_count_name').html(newstr);
                        $('#marge_count_count').html(answer['count']);
                    }
                }
            });
        }

        function changeExportInputs() {
            var partner_id = $('#partner_id').val();
            var group_id = $('#group_id').val();
            var tracker_id = $('#tracker_id').val();
            var creative_id = $('#creative_id').val();
            var brands_id = $('#brands_id').val();
            if (brands_id) {
            } else {
                brands_id = $('#brands_id').find("option:first-child").val()
            }
            $('#export_brands_id').val(brands_id);
            if (creative_id) {
            } else {
                creative_id = $('#creative_id').find("option:first-child").val()
            }
            $('#export_creative_id').val(creative_id);
            if (tracker_id) {
            } else {
                tracker_id = $('#tracker_id').find("option:first-child").val()
            }
            $('#export_tracker_id').val(tracker_id);
            if (group_id) {
            } else {
                group_id = $('#group_id').find("option:first-child").val()
            }
            $('#export_group_id').val(group_id);
            if (partner_id) {
            } else {
                partner_id = $('#partner_id').find("option:first-child").val()
            }
            $('#export_id').val(partner_id);
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var date = Array.from($('#date').val().match(regexp));
            var partner_first_date = '';
            var partner_last_date = '';
            if(date !== undefined){
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            $('#export_first_date').val(partner_first_date);
            $('#export_last_date').val(partner_last_date);
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
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var date = Array.from($('#date').val().match(regexp));
            var partner_first_date = '';
            var partner_last_date = '';
            if(date !== undefined){
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            var partner_id = $('#partner_id').val();
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
            if (partner_id) {
            } else {
                partner_id = $('#partner_id').find("option:first-child").val()
            }
            if (tracker_id) {
            } else {
                tracker_id = $('#tracker_id').find("option:first-child").val()
            }
            $.ajax({
                type: 'post',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('get_reports_get_total')}}',
                data: {
                    partner_id: partner_id,
                    brands_id: brands_id,
                    creative_id: creative_id,
                    tracker_id: tracker_id,
                    partner_first_date: partner_first_date,
                    partner_last_date: partner_last_date,
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

            async function getTrackers() {
                let id = $('#partner_id').val();
                if (id) {
                } else {
                    id = $('#partner_id').find("option:first-child").val()
                }
                await $.ajax({
                    type: 'post',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('get_tracker_for_partner')}}',
                    data: {id: id},
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
                            $('#tracker_id').append(new Option('choose a partner first',''));
                            $("#tracker_id").attr("disabled", true);
                            return false;
                        }
                    }
                });
            }

            async function getBrands(){
                let id = $('#partner_id').val();
                if (id) {
                } else {
                    id = $('#partner_id').find("option:first-child").val()
                }
                await $.ajax({
                    type: 'post',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('get_brands_for_partner')}}',
                    data: {id: id},
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
                            $('#brands_id').append(new Option('choose a partner first',''));
                            $("#brands_id").attr("disabled", true);
                            return false;
                        }
                    }
                });
            }

            async function loadData(){
                $.fn.dataTable.ext.errMode = 'none';
                var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
                var date = Array.from($('#date').val().match(regexp));
                var partner_first_date = '';
                var partner_last_date = '';
                if(date !== undefined){
                    partner_first_date = date[1];
                    partner_last_date = date[4];
                }
                var group_id = $('#group_id').val();
                var partner_id = $('#partner_id').val();
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
                if (partner_id) {
                } else {
                    partner_id = $('#partner_id').find("option:first-child").val()
                }
                if (tracker_id) {
                } else {
                    tracker_id = $('#tracker_id').find("option:first-child").val()
                }
                if (group_id) {
                } else {
                    group_id = $('#group_id').find("option:first-child").val()
                }
                await $('#myTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        "url": '{!! route('admin_reports_get_general_reports') !!}',
                        "data": {
                            "partner_id": partner_id,
                            "brands_id": brands_id,
                            "creative_id": creative_id,
                            "group_id": group_id,
                            "tracker_id": tracker_id,
                            "partner_first_date": partner_first_date,
                            "partner_last_date": partner_last_date,
                        }
                    },
                    order: [3, "desc"],
                    columns: columns(),
                    initComplete: async function (settings, json) {
                        await total();
                    }
                });}

            $('#date').change(function () {
                var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
                var date = Array.from($('#date').val().match(regexp));
                var partner_first_date = date[1];
                var partner_last_date = date[4];
                if(date !== undefined){
                    $('#export_last_date').val(partner_last_date);
                    $('#export_first_date').val(partner_first_date);
                }
            });
            $('#partner_id').change(async function () {
                $('#export_id').val($('#partner_id').val());
                await getTrackers();
                await getBrands();
            });
            $('#creative_id').change(async function () {
                $('#export_creative_id').val($('#creative_id').val());
            });
            $('#brands_id').change(async function () {
                $('#export_brands_id').val($('#brands_id').val());
            });
            $('#group_id').change(function () {
                $('#export_group_id').val($('#group_id').val());
            });
            $('#tracker_id').change(function () {
                $('#export_tracker_id').val($('#tracker_id').val());
            });

            await getMargeCount();
            loadData();
            changeExportInputs();
        })
    </script>
    <!-- Page JS Code -->
    <script src="{{asset('/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Page JS Helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins) -->
    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function () {
            Codebase.helpers(['maxlength', 'select2', 'masked-inputs', 'rangeslider', 'tags-inputs', 'select2']);
        });</script>
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
    {{--<script src="{{asset('/js/pages/be_tables_datatables.min.js')}}"></script>--}}
@endsection
