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
            <h3 class="block-title">Detailed report</h3>
            <form action="{{route('admin_export_partner')}}" method="POST">
                @csrf
                <input id="export_id" type="hidden" name="id" value="">
                <input id="export_report_id" type="hidden" name="report_id" value="">
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
                    <select class="js-select2 form-control" id="partner_id" name="id"
                            placeholder="Select partner id..">
                        @foreach($partners as $partner)
                            <option value="{{$partner['id']}}">{{$partner['name']}}</option>
                        @endforeach
                    </select>
                </div>
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
                <div id="report_div" class="form-group">
                    <label for="report_select">Report type</label>
                    <select class="form-control mb-2 mr-sm-2 mb-sm-0" id="report_select" name="report_id" disabled>
                        <option value="">choose a partner first</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="text" id="date" class="form-control" name="partner_date" value="01-01-2019 - 01-15-2019"/>
                </div>
            </div>
            <div class="col-md-3 mt-md-3">
                <div class="form-group">
                    <label for="brands_id">Brands</label>
                    <select class="js-select2 form-control" id="brands_id" name="id" disabled>
                        <option value="">choose a partner first</option>
                    </select>
                </div>
                <div id="creative_div" class="form-group" style="display: none">
                    <label for="creative_id">Creative</label>
                    <input type="text" id="creative_id" class="form-control" name="creative_id" value="">
                </div>
            </div>
            <div class="col-md-3 mt-md-3">
                <div id="tracker_div" class="form-group" style="display: none">
                    <label for="tracker_id">Trackers</label>
                    <select class="js-select2 form-control" id="tracker_id" name="id" disabled>
                        <option value="">choose a partner first</option>
                    </select>
                </div>
                <div class="form-group mt-4 pt-2">
                    <button onclick="search()" type="button" class="btn btn-alt-primary btn-block mt-3">Apply filters</button>
                </div>
            </div>
            </form>
            <div id="table_update" class="table-responsive">
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
            <div id="marge_count" class="col-md-3 mt-md-3" style="display: none">
                <div class="form-group">
                    <label id="marge_count_name" for="creative_id"></label>
                    <p id="marge_count_count"></p>
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
        async function changeAllTable() {
            let res = await getCheckValidColumns();
            let answer = JSON.parse(res);
            await createNewTable();
            var newColumns = answer;
            $('tbody').html('');
            await changeThead(newColumns);
            return answer;
        }

        function createOriginalTotalColums(columns) {
            let arr = '';
            for (let i = 0; i < columns.length; i++) {
                arr += '<td>' + columns[i] + '</td>';
            }
            return '<tr>' + arr + '</tr>'
        }

        async function getCheckValidColumns() {
            let partner_id = $('#partner_id').val();
            if (partner_id) {
                partner_id = $('#partner_id').find("option:first-child").val()
            }
            let report = $('#report_select').val();
            if (report) {
            } else {
                report = $('#report_select').find("option:first-child").val()
            }
            let response_json = 'true';
            await $.ajax({
                type: 'post',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('check_valid_report_columns')}}',
                data: {
                    partner_id: partner_id,
                    report: report,
                },
                success: function (response) {
                    response_json = response;
                }
            });
            return response_json;
        }

        function createColumns(columns) {
            let arr = new Array();
            for (let i = 0; i < columns.length; i++) {
                arr.push({data: columns[i], name: columns[i]});
            }
            return arr;
        }

        function createNewTable() {
            let table = '<table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable"\n' +
                'style="width:100%">' +
                '<thead>' +
                '<\/thead>' +
                '<\/table>';
            $('#table_update').html(table);
            return true;
        }

        function changeThead(columns) {
            let arr = '';
            for (let i = 0; i < columns.length; i++) {
                arr += '<td class="align-middle text-center">' + columns[i] + '</td>';
            }
            $('thead').html('<tr>' + arr + '</tr>');
            return true;
        }

        async function search() {
            $.fn.dataTable.ext.errMode = 'none';
            let url = '{!! route('admin_reports_get_detail_original_reports') !!}';
            var partner_id = $('#partner_id').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var date = Array.from($('#date').val().match(regexp));
            var partner_first_date = '';
            var partner_last_date = '';
            if(date !== undefined){
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            let report = $('#report_select').val();
            if (report) {
                report = $('#report_select').find("option:first-child").val()
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
            var answer = await changeAllTable();
            var columns = await createColumns(answer);
            $('#myTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    data: {
                        partner_id: partner_id,
                        partner_first_date: partner_first_date,
                        partner_last_date: partner_last_date,
                        report: report,
                        brands_id: brands_id,
                        creative_id: creative_id,
                        group_id: group_id,
                        tracker_id: tracker_id,
                    }
                },
                order: [3, "desc"],
                columns: columns,
                initComplete: async function (settings, json) {
                    await total(true);
                    await getMargeCount();
                }
            });
        }

        function total(original = true) {
            if (original !== true) {
                url = '{!!route('get_reports_get_total')!!}';
            } else {
                url = '{!!route('get_reports_get_original_total')!!}';
            }
            var partner_id = $('#partner_id').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var date = Array.from($('#date').val().match(regexp));
            var partner_first_date = '';
            var partner_last_date = '';
            if(date !== undefined){
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            var report = $('#report_select').val();
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
                url: url,
                data: {
                    partner_id: partner_id,
                    partner_first_date: partner_first_date,
                    partner_last_date: partner_last_date,
                    report: report,
                    brands_id: brands_id,
                    creative_id: creative_id,
                    group_id: group_id,
                    tracker_id: tracker_id,
                },
                success: async function (response) {
                    var answer = JSON.parse(response);
                    if (answer !== false) {
                        var totalColumns = await createOriginalTotalColums(answer);
                        $('tbody').append('<tr>' + totalColumns + '</tr>');
                    }
                }
            })
        }

        async function getMargeCount(){
            var partner_id = $('#partner_id').val();
            var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            var date = Array.from($('#date').val().match(regexp));
            var partner_first_date = '';
            var partner_last_date = '';
            if(date !== undefined){
                partner_first_date = date[1];
                partner_last_date = date[4];
            }
            let report = $('#report_select').val();
            if (report) {
                report = $('#report_select').find("option:first-child").val()
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

        $(document).ready(async function () {

            async function takeReports() {
                var id = $("#partner_id").val();
                await $.ajax({
                    type: 'post',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('get_reports_for_partner')}}',
                    data: {id: id},
                    success: async function (response) {
                        var answer = JSON.parse(response);
                        if (answer !== false) {
                            $('#report_select').empty();
                            answer.forEach(await function (element) {
                                var re = /_/gi;
                                var newstr = element.replace(re, ' ');
                                $('#report_select').append(new Option(newstr, element));
                            });
                            await changeExportInputs();
                            $("#report_select").attr("disabled", false);
                            return true;
                        } else {
                            return false;
                        }
                    }
                });
            }

            async function getCheckCreative() {
                var partner_id = $('#partner_id').val();
                let report = $('#report_select').val();
                if (report) {
                }else{
                    report = $('#report_select').find("option:first-child").val()
                }
                await $.ajax({
                    type: 'post',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('get_check_for_creative')}}',
                    data: {partner_id: partner_id,report: report},
                    success: async function (response) {
                        var answer = JSON.parse(response);
                        if (answer != false) {
                            await changeExportInputs();
                            $('#creative_div').show();
                            return true;
                        } else {
                            $('#creative_div').hide();
                            return false;
                        }
                    }
                });
            }

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
                            $('#tracker_div').show();
                            $("#tracker_id").attr("disabled", false);
                            return true;
                        } else {
                            $('#tracker_id').empty();
                            $('#tracker_div').hide();
                            $('#tracker_id').append(new Option('choose a partner first', ''));
                            $("#tracker_id").attr("disabled", true);
                            return false;
                        }
                    }
                });
            }

            async function getBrands() {
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
                            $('#brands_id').append(new Option('choose a partner first', ''));
                            $("#brands_id").attr("disabled", true);
                            return false;
                        }
                    }
                });
            }

            async function load_data() {
                $.fn.dataTable.ext.errMode = 'none';
                let url = '{!! route('admin_reports_get_detail_original_reports') !!}';
                var partner_id = $('#partner_id').val();
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
                let report = $('#report_select').val();
                if (report) {
                }else{
                    report = $('#report_select').find("option:first-child").val()
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
                var answer = await changeAllTable();
                var columns = await createColumns(answer);
                $('#myTable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: url,
                        data: {
                            partner_id: partner_id,
                            partner_first_date: partner_first_date,
                            partner_last_date: partner_last_date,
                            report: report,
                            brands_id: brands_id,
                            creative_id: creative_id,
                            group_id: group_id,
                            tracker_id: tracker_id
                        }
                    },
                    order: [3, "desc"],
                    columns: columns,
                    initComplete: async function (settings, json) {
                        await total(true);
                        //await search();
                    }
                });
            }

            function changeExportInputs() {
                $('#export_id').val($('#partner_id').val());
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
                let element = $('#report_select').val();
                if (element) {
                } else {
                    element = $('#report_select').find("option:first-child").val()
                }
                $('#export_report_id').val(element);
            }

            $('#date').change(function () {
                var regexp = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])) - ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
                var date = Array.from($('#date').val().match(regexp));
                var partner_first_date = '';
                var partner_last_date = '';
                if(date !== undefined){
                    partner_first_date = date[1];
                    partner_last_date = date[4];
                }
                $('#export_last_date').val(partner_last_date);
                $('#export_first_date').val(partner_first_date);
            });
            $('#report_select').change(async function () {
                $('#export_report_id').val($('#report_select').val());
                await getCheckCreative();
            });
            $('#partner_id').change(async function () {
                $('#export_partner_id').val($('#partner_id').val());
                await takeReports();
                await getTrackers();
                await getBrands();
                await getCheckCreative();
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

            await takeReports();
            await changeExportInputs();
            await getTrackers();
            await getBrands();
            await getCheckCreative();
            await getMargeCount();
            load_data();
        });
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
