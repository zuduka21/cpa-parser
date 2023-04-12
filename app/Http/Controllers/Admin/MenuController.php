<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ParserFieldEmpty;
use App\Http\Requests\PartnersSearchRequest;
use App\Partner;
use Arcanedev\LogViewer\Http\Controllers\LogViewerController;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PartnersRepository;
use App\Repositories\BrandsRepository;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    private $partnersRepository;
    private $brandsRepository;

    public function __construct()
    {
        $this->middleware('auth');
        $this->partnersRepository = app(PartnersRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
    }

    public function index()
    {
        return view('admin.index', array(       ));
    }
}
