<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UsersUpdateRequest;
use App\Http\Controllers\Controller;
use App\Repositories\UsersRepository;
use App\Repositories\BrandsRepository;
use App\Repositories\CountriesRepository;
use App\Repositories\PartnersRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    private $userRepository;
    private $brandsRepository;
    private $countriesRepository;
    private $partnersRepository;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.admin');
        $this->userRepository = app(UsersRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
        $this->countriesRepository = app(CountriesRepository::class);
        $this->partnersRepository = app(PartnersRepository::class);
    }

    public function create()
    {
        $brands = $this->brandsRepository->getAll();
        $countries = $this->countriesRepository->getAll();
        $roles = config('users.roles');
        $partners = $this->partnersRepository->getAll();

        return view('admin.users.register', array(
            'brands' => $brands,
            'roles' => $roles,
            'countries' => $countries,
            'partners' => $partners,
        ));
    }

    public function index()
    {
        return view('admin.users.index', array());
    }

    public function getUsers()
    {
        $users = $this->userRepository->getAllUsers();
        return DataTables::of($users)->
        addColumn('role', function ($user) {
            return $user->roles->first()->name;
        })->
        addColumn('buttons', 'components.user_buttons')->
        rawColumns(['buttons'])->
        setRowAttr(['align' => 'center'])->
        toJson();
    }

    public function delete($user_id)
    {
        if (!empty($user_id) && Auth::id() != $user_id) {
            $user = $this->userRepository->getModel($user_id);
            if (!empty($user)) {
                Log::info('user', [$user['name'] => 'Has be created','userId'=>Auth::id()]);
                $user->delete();
            }
        }
        return redirect()->route('admin_users_index');
    }

    public function edit($id)
    {
        $user = $this->userRepository->getModel($id);
        if (empty($user)) {
            return back()->withErrors(['msg' => 'Not find!'])->withInput();
        }
        $brands = $this->brandsRepository->getAll();
        $countries = $this->countriesRepository->getAll();
        $roles = config('users.roles');
        $partners = $this->partnersRepository->getAll();
        return view('admin.users.edit', array(
            'user' => $user,
            'brands' => $brands,
            'roles' => $roles,
            'countries' => $countries,
            'partners' => $partners,
        ));
    }

    public function update(UsersUpdateRequest $request)
    {
        $inputData = $request->all();
        $user = $this->userRepository->getEdit($inputData['id']);
        if (!empty($user)) {
            $user->roles()->detach();
            if(!empty($request['role'])){
                $role = Role::where('name', $request['role'])->first();
                if (!empty($role)) {
                    $user->roles()->save($role);
                }
            }else{
                $role = Role::where('name', 'user')->first();
                if (!empty($role)) {
                    $user->roles()->save($role);
                }
            }
            if(!empty($inputData['allow'])) {
            $user->brands()->detach();
            $user->countries()->detach();
            $user->partners()->detach();
                if ($inputData['allow'] == 1 && !empty($inputData['partner_id'])) {
                    $user->partners()->attach($inputData['partner_id']);
                } else if ($inputData['allow'] == 2 && !empty($inputData['country_id'])) {
                    $user->countries()->attach($inputData['country_id']);
                } else if ($inputData['allow'] == 3 && !empty($inputData['brand_id'])) {
                    $user->brands()->attach($inputData['brand_id']);
                }
            }
            $inputData['allow_export'] = (!empty($inputData['allow_export'])) ? true : false;

            $user->fill($inputData);
            $user->save();
            Log::info('user', [$user['name'] => 'Has be created','userId'=>Auth::id()]);
            return redirect()->route('admin_users_index');
        }
        return back()->withErrors(['msg' => 'Something went wrong!'])->withInput();
    }
}
