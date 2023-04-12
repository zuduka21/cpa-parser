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
                <label for="date">Date</label>
                <input type="text" id="date" class="form-control" name="partner_date" value="01-01-2019 - 01-15-2019"/>
            </div>
        </div>
    </div>

    <div class="row gutters-tiny">
        <div class="col-md-6 col-xl-3">
            <a class="block border block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="py-50 text-center">
                        <div class="font-size-h2 font-w700 text-dark" id="Clicks"></div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Clicks</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3">
            <a class="block border block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="py-50 text-center">
                        <div class="font-size-h2 font-w700 text-success" id="Total_Profit"></div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Total Profit</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3">
            <a class="block border block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full text-right">
                    <div class="py-50 text-center">
                        <div class="font-size-h2 font-w700 text-dark" id="Signups"></div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Signups</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3">
            <a class="block border block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full text-right">
                    <div class="py-50 text-center">
                        <div class="font-size-h2 font-w700 text-dark" id="FTDC"></div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">FTDC</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

@section('pagescripts')
    <!-- Page JS Plugins -->
    <script src="{{asset('/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Page JS Helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins) -->
    {{--calendar--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    {{--endcalendar--}}
    <script>
        function search(){
            getInfo();
        }
        async function getInfo(){
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
            var partner_id = $('#partner_id').val();
            if (partner_id) {
            } else {
                partner_id = $('#partner_id').find("option:first-child").val()
            }
            await $.ajax({
                type: 'post',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('admin_dashboard_get_info')}}',
                data: {
                    partner_id: partner_id,
                    partner_first_date: partner_first_date,
                    partner_last_date: partner_last_date,
                },
                success: function (response) {
                    var answer = JSON.parse(response);
                    if(answer != false){
                    $('#Clicks').html(answer['Clicks']);
                    $('#Total_Profit').html(answer['Total_Profit']);
                    $('#Signups').html(answer['Signups']);
                    $('#FTDC').html(answer['FTDC']);
                    }
                    console.log(answer);
                }
            });
        }
        $(document).ready(async function () {
           await getInfo();
            $('#date').change(async function () {
                await getInfo();
            });
            $('#partner_id').change(async function () {
                await getInfo();
            });
        })
    </script>
    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function(){ Codebase.helpers(['maxlength', 'select2', 'masked-inputs', 'rangeslider', 'tags-inputs', 'select2']); });</script>
    {{--<script src="{{asset('/js/pages/be_tables_datatables.min.js')}}"></script>--}}
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
