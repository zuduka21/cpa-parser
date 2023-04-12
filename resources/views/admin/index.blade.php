@extends('admin.layouts.parser')

@section('pagejsplugins')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('/js/plugins/datatables/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('/js/plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <div class="block">
    </div>
@endsection

@section('pagescripts')
    <!-- Page JS Plugins -->
    <script src="{{asset('/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Page JS Helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins) -->

    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function(){ Codebase.helpers(['datepicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider', 'tags-inputs', 'select2']); });</script>
    {{--<script src="{{asset('/js/pages/be_tables_datatables.min.js')}}"></script>--}}
@endsection
