<?php

//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

Route::get('/', 'Admin\MenuController@index')->name('index');

Route::group(['namespace' => 'User','prefix' => 'user'], function () {
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', 'ReportsController@index')->name('user_index');
        Route::post('/get_total', 'ReportsController@getTotal')->name('user_reports_get_total');
        Route::post('/get_trackers', 'ReportsController@getTrackers')->name('user_reports_get_trackers');
        Route::post('/get_brands', 'ReportsController@getBrands')->name('user_reports_get_brands');
        Route::get('/get_reports', 'ReportsController@getReports')->name('user_reports_get_reports');
    });
    Route::group(['prefix' => 'export'], function () {
        Route::post('/allow_brands', 'ExportController@export')->name('user_export_allow_brands');
    });
});

Route::group(['namespace' => 'Admin','prefix' => 'admin'], function () {
    Route::group(['prefix' => 'menu'], function () {
        Route::get('/', 'MenuController@index')->name('admin_index');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('new', 'UsersController@create')->name('admin_users_create');
        Route::get('/', 'UsersController@index')->name('admin_users_index');
        Route::get('delete/{id}', 'UsersController@delete')->name('admin_users_delete');
        Route::get('edit/{id}', 'UsersController@edit')->name('admin_users_edit');
        Route::post('update', 'UsersController@update')->name('admin_users_update');
        Route::get('all_users/', 'UsersController@getUsers')->name('admin_users_get_users');
    });
    Route::group(['prefix' => 'partners'], function () {
        Route::get('/', 'ParserController@index')->name('admin_partners_index');
        Route::get('all_partners/', 'ParserController@getPartners')->name('admin_partners_get_partners');
        Route::get('edit/{indication}', 'ParserController@edit')->name('admin_partners_edit');
        Route::post('update', 'ParserController@update')->name('admin_partners_update');
        Route::get('create', 'ParserController@create')->name('admin_partners_create');
        Route::post('store', 'ParserController@store')->name('admin_partners_store');
        Route::get('delete/{id}', 'ParserController@delete')->name('admin_partners_delete');
        Route::get('updateAll', 'ParserController@updateAll')->name('admin_partners_update_info');
    });
    Route::group(['prefix' => 'countries'], function () {
        Route::get('/', 'CountriesController@index')->name('admin_countries_index');
        Route::get('all_countries/', 'CountriesController@getCountries')->name('admin_countries_get_countries');
        Route::get('edit/{id}', 'CountriesController@edit')->name('admin_countries_edit');
        Route::post('update', 'CountriesController@update')->name('admin_countries_update');
        Route::get('create', 'CountriesController@create')->name('admin_countries_create');
        Route::post('store', 'CountriesController@store')->name('admin_countries_store');
        Route::get('delete/{id}', 'CountriesController@delete')->name('admin_countries_delete');
    });
    Route::group(['prefix' => 'reports'], function () {
        Route::get('general/', 'ReportsController@index')->name('admin_reports_general');
        Route::get('general_reports/', 'ReportsController@getGeneral')->name('admin_reports_get_general_reports');

        Route::get('detail/', 'ReportsController@detail')->name('admin_reports_detail');
        Route::get('detail_reports/', 'ReportsController@getDetail')->name('admin_reports_get_detail_reports');
        Route::get('original_detail_reports/', 'ReportsController@getOriginalDetail')->name('admin_reports_get_detail_original_reports');
        Route::post('get_reports/', 'ReportsController@getReports')->name('get_reports_for_partner');
        Route::post('get_creative/', 'ReportsController@getCheckCreative')->name('get_check_for_creative');
        Route::post('get_brands/', 'ReportsController@getBrands')->name('get_brands_for_partner');
        Route::post('get_trackers/', 'ReportsController@getTrackers')->name('get_tracker_for_partner');
        Route::post('get_total/', 'ReportsController@getTotal')->name('get_reports_get_total');
        Route::post('get_original_total/', 'ReportsController@getOriginalTotal')->name('get_reports_get_original_total');
        Route::post('check_valid/', 'ReportsController@checkColumnsValid')->name('check_valid_report_columns');
        Route::post('get_marge_count/', 'ReportsController@getMargeCount')->name('get_marge_count');

    });
    Route::group(['prefix' => 'export'], function () {
        Route::post('partner/', 'ExportController@export')->name('admin_export_partner');
        Route::post('partner/general', 'ExportController@exportGeneral')->name('admin_export_partner_general');
    });
    Route::group(['prefix' => 'logs'], function () {
        Route::get('/', 'LogsController@index')->name('admin_logs_index');
        Route::get('get_logs/', 'LogsController@getLogs')->name('admin_logs_get_all');
    });
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', 'DashboardController@index')->name('admin_dashboard_index');
        Route::post('/get_info', 'DashboardController@getInfo')->name('admin_dashboard_get_info');
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/psw', function(){
    return view('demo');
});
