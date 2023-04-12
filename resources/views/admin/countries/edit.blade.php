@extends('admin.layouts.parser')

@section('pagejsplugins')
    <link rel="stylesheet" href="{{asset('/js/plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{route('admin_countries_index')}}" class="btn btn-primary">Return back</a>
    </div>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit country "{{$country->name}}"</h3>
        </div>
        <div class="block-content">
            <div class="row py-20">
                <div class="col-xl-6">
                    <form class="js-validation-bootstrap" action="{{route('admin_countries_update')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$country->id}}">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="val-username">Country name
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ $country->name }}" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="brands_id">Brands</label>
                            <div class="col-lg-8">
                                <select class="js-select2 form-control" id="brands_id" name="brands_id[]" style="width: 100%;" data-placeholder="Choose brands.." multiple>
                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                    @foreach($brands as $brand)
                                        <option value="{{$brand->id}}" {{($country->brands->where('id',$brand->id)->count()) ? "selected" :""}}>{{$brand->name}}</option>
                                    @endforeach
                                </select>
                                @error('brands_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-8 ml-auto">
                                <button type="submit" class="btn btn-primary">Save country</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescripts')
    <script src="{{asset('/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/js/plugins/jquery-validation/additional-methods.js')}}"></script>

    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function () {
            Codebase.helpers('select2');
        });</script>

    <!-- Page JS Code -->
    <script src="{{asset('/js/pages/be_forms_validation.min.js')}}"></script>
@endsection
