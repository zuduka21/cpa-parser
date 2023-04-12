<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UsersRepository;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Arcanedev\LogViewer\Helpers\LogParser;
use Yajra\DataTables\DataTables;
use Arcanedev\LogViewer\Entities\LogEntry;

class LogsController extends Controller
{

    private $userRepository;
    private $log_tabs;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.admin');
        $this->userRepository = app(UsersRepository::class);
        $this->log_tabs = config('self_logs.tabs');
    }

    public function index()
    {
        $logs = $this->log_tabs;
        return view('admin.logs.index', array(['logs_list' => $logs]));
    }

    public function getLogs()
    {
        $first_date = '';
        $last_date = '';
        $log_id = '';
        if (\request()->has('first_date') && !empty(\request('first_date'))) {
            $first_date = \request('first_date');
        }
        if (\request()->has('last_date') && !empty(\request('last_date'))) {
            $last_date = \request('last_date');
        }
        if (\request()->has('log_id') && !empty(\request('log_id'))) {
            $log_id = \request('log_id');
        }
        $tera = $this->getInfo();
        $res = array();
        foreach ($tera as $item) {
            $LogEntry = new LogEntry($item['level'], $item['header'], $item['stack']);
            $data = $LogEntry->jsonSerialize();
            $user_name = $this->getUser($data['header']);
            $data['header'] = preg_replace('/\"userId\":[\d]+/', '', $data['header']);
            $data['header'] = str_replace('.', '', $data['header']);

            $reg_tab = '/["\s\d\w]+{/';
            $temp = preg_replace($reg_tab, '', $data['header']);
            $data['header'] = preg_replace('/[,]*}["\s\d\w":.,]*/', '', $temp);
            $data['user'] = $user_name;
            $data['stack'] = '';
            $stripped = $this->getTab($item['header']);
            if(!empty($first_date) && !empty($last_date)){
                if ($data['datetime'] >= $first_date && $data['datetime'] <= $last_date) {
                    if (in_array($stripped, $this->log_tabs)) {
                        $res[$stripped][] = $data;
                    } else {
                        $res['another'][] = $data;
                    }
                }
            }
            else if(!empty($first_date)) {
                if ($data['datetime'] >= $first_date) {
                    if (in_array($stripped, $this->log_tabs)) {
                        $res[$stripped][] = $data;
                    } else {
                        $res['another'][] = $data;
                    }
                }
            }
            else if(!empty($last_date)){
                if ($data['datetime'] <= $last_date) {
                    if (in_array($stripped, $this->log_tabs)) {
                        $res[$stripped][] = $data;
                    } else {
                        $res['another'][] = $data;
                    }
                }
            }
            else{
                if (in_array($stripped, $this->log_tabs)) {
                    $res[$stripped][] = $data;
                } else {
                    $res['another'][] = $data;
                }
            }

        }
        $collect = collect();
        if(!empty($res[$log_id])){
            $collect = collect($res[$log_id]);
        }
        return DataTables::of($collect)->toJson();
    }

    private function getTab($header)
    {
        $reg_tab = '/(local.[a-zA-Z]+[:] [a-zA-Z]+)/';
        $data_temp = preg_split("/[\d]+\] /", $header);
        preg_match($reg_tab, $data_temp[1], $info_tab);
        if(!empty($info_tab[0])){
            $tab_temp = preg_split("/:/", $info_tab[0]);
        }else if(!empty($info_tab[1])){
            $tab_temp = preg_split("/:/", $info_tab[1]);
        }
        if(!empty($tab_temp[1])){

            $stripped = str_replace(' ', '', $tab_temp[1]);
        }else{
            $stripped = 'another';
        }
        return $stripped;
    }

    private function getUser($header)
    {
        $user_name = 'none';
        $reg_userId = '/\"userId\":[\d]+/';
        preg_match($reg_userId, $header, $userId);
        if (!empty($userId[0])) {
            $user_id = preg_split("/:/", $userId[0]);
            $user_id = str_replace(' ', '', $user_id[1]);
            $user = $this->userRepository->getEdit($user_id);
            $user_name = $user['name'];
        }
        return $user_name;
    }

    private function getInfo()
    {
        $logsFiles = array_filter(Storage::disk('local_logs')->files(),
            function ($item) {
                return strpos($item, 'log');
            });
        $marge_info_array = array();
        foreach ($logsFiles as $file) {
            $content = Storage::disk('local_logs')->get($file);
            $logParser = new LogParser();
            $tera = $logParser->parse($content);
            $marge_info_array = array_merge($tera, $marge_info_array);
        }
        return $marge_info_array;
    }
}
