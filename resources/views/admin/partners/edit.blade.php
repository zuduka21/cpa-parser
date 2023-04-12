@extends('admin.layouts.parser')

@section('pagejsplugins')

@endsection

@section('content')
    <div class="mb-3">
        <a href="{{route('admin_partners_index')}}" class="btn btn-primary">Return back</a>
    </div>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit partner "{{ $partner->name }}"</h3>
        </div>
        <div class="block-content">
            <div class="row py-20">
                <div class="col-xl-6">
                    <form class="js-validation-bootstrap" action="{{route('admin_partners_update')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$partner->id}}">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="name">Name
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $partner->name }}" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="shift_date">Shift date
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="date" id="shift_date" class="form-control @error('shift_date') is-invalid @enderror" name="shift_date" value="{{ $partner->shift_date }}" autocomplete="shift_date" autofocus required>
                                @error('shift_date')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="login">Partner login
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" id="login" class="form-control @error('login') is-invalid @enderror" name="login" value="{{$partner->login }}" autocomplete="login" autofocus required>
                                @error('login')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="password">Password
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="login" autofocus required>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Activate parser<span class="text-danger"></span></label>
                            <div class="col-lg-8">
                                <label class="css-control css-control-primary css-checkbox" for="working">
                                    <input type="checkbox" class="css-control-input" id="working" name="working" {{ ($partner['working']) ? 'checked': '' }}>
                                    <span class="css-control-indicator"></span> Yes
                                </label>
                            </div>
                            @error('working')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 ml-auto">
                                <button type="submit" class="btn btn-primary">Save partner</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescripts')
    <!-- Page JS Plugins -->
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
