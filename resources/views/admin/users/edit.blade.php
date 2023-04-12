@extends('admin.layouts.parser')

@section('pagejsplugins')
    <link rel="stylesheet" href="{{asset('/js/plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{route('admin_users_index')}}" class="btn btn-primary">Return back</a>
    </div>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit user "{{$user->name}}"</h3>
        </div>
        <div class="block-content">
            <div class="row py-20">
                <div class="col-xl-6">
                    <form class="js-validation-bootstrap" action="{{route('admin_users_update')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="val-username">Username
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="email">Email
                                <span class="text-danger"></span></label>
                            <div class="col-lg-8">
                                <input type="text" id="email" class="form-control"
                                       name="email" placeholder="Your valid email.." value="{{$user->email}}"
                                       autocomplete="email" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="password">Password</label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" id="password" placeholder="Enter password (min 8 char)">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="val-confirm-password">Confirm Password</label>
                            <div class="col-lg-8 ">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" autocomplete="new-password"
                                       placeholder="... and confirm it!">
                            </div>
                        </div>
                        <div id="roles" class="form-group row">
                            <label class="col-lg-4 col-form-label" for="roles">Roles</label>
                            <div class="col-lg-8 ">
                                <select id="roles" name="role" class="js-select2 form-control">
                                    @foreach($roles as $role)
                                        <option value="{{$role}}" {{ ($user->checkRole($role)) ? 'selected' : "" }}>{{$role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(!$user->checkRole('admin'))
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Allow export</label>
                            <div class="col-lg-8">
                                <label class="css-control css-control-primary css-checkbox" for="allow_export">
                                    <input type="checkbox" class="css-control-input" id="allow_export" name="allow_export" {{ ($user->allow_export) ? 'checked': '' }}>
                                    <span class="css-control-indicator"></span> Yes
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="allow_one">Give access</label>
                            <div class="col-lg-8 ">
                                <select id="allow_just_one" name="allow" class="form-control">
                                    <option value="1" {{ ($user->NotEmptyPartners()) ? 'selected' : "" }}>Partners</option>
                                    <option value="2" {{ ($user->NotEmptyCountries()) ? 'selected' : "" }}>Countries</option>
                                    <option value="3" {{ ($user->NotEmptyBrands()) ? 'selected' : "" }}>Brands</option>
                                </select>
                            </div>
                        </div>

                        <div id="allow_1" class="form-group row" style="display: none">
                            <label class="col-lg-4 col-form-label" for="allow_one">Partners</label>
                            <div class="col-lg-8 ">
                                <select  id="allow_one" name="partner_id[]" class="js-select2 form-control" multiple>
                                    @foreach($partners as $partner)
                                        <option value="{{$partner->id}}" {{($user->partners->where('id',$partner->id)->count()) ? "selected" :""}}>{{$partner->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="allow_2" class="form-group row" style="display: none">
                            <label class="col-lg-4 col-form-label" for="allow_two">Countries</label>
                            <div class="col-lg-8 ">
                                <select id="allow_two" name="country_id[]" class="js-select2 form-control" multiple>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" {{($user->countries->where('id',$country->id)->count()) ? "selected" :""}}>{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="allow_3" class="form-group row" style="display: none">
                            <label class="col-lg-4 col-form-label" for="allow_tree">Brands</label>
                            <div class="col-lg-8 ">
                                <select id="allow_tree" name="brand_id[]" class="js-select2 form-control" multiple>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand->id}}" {{($user->brands->where('id',$brand->id)->count()) ? "selected" :""}}>{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-lg-8 ml-auto">
                                <button type="submit" class="btn btn-primary">Save user</button>
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
    <script src="{{asset('/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/js/plugins/jquery-validation/additional-methods.js')}}"></script>

    <script>
        function openSelector() {
            for (var i = 1; i <= 3; i++) {
                $("#allow_" + i).hide();
            }
            var value = $("#allow_just_one").val();
            console.log(value);
            $("#allow_" + value).show();
        }
        $(document).ready(function () {
            openSelector();
            $("#allow_just_one").change(function () {
                openSelector();
            });
        })
    </script>

    <script src="{{asset('/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function () {
            Codebase.helpers('select2');
        });</script>

    <!-- Page JS Code -->
    <script src="{{asset('/js/pages/be_forms_validation.min.js')}}"></script>
@endsection
