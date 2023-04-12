@extends('admin.layouts.parser')

@section('pagejsplugins')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('/js/plugins/datatables/dataTables.bootstrap4.css')}}">
@endsection

@section('content')
    <!-- Dynamic Table Full -->
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Countries
                <a href="{{ route('admin_countries_create') }}" class="btn btn-sm btn-primary pull-right" data-toggle="tooltip" title="Add new user">
                    Add country
                </a>
            </h3>
        </div>
        <div class="block-content block-content-full">
            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="myTable" style="width:100%">
                <thead>
                <tr>
                    <th class="text-center">id</th>
                    <th>name</th>
                    <th class="text-center" style="width: 15%;">buttons</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <!-- END Dynamic Table Full -->
@endsection

@section('pagescripts')
    <!-- Page JS Plugins -->
    <script src="{{asset('/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        function columns() {
            return [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'buttons', name: 'buttons'},
            ];
        }
        $.fn.dataTable.ext.errMode = 'none';
        $(document).ready(function () {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin_countries_get_countries') !!}',
                columns: columns()
            })
        })
    </script>
    <script>
        function deleteEl(id) {
            var isAdmin = confirm("Вы - администратор?");
        }
    </script>
@endsection
