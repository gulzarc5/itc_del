<?php
Route::group(['namespace'=>'Admin'],function(){
    Route::get('/', 'LoginController@showAdminLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@adminLogin');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');

    Route::group(['middleware'=>'auth:admin','prefix'=>'admin'],function(){
        Route::get('/dashboard', 'DashboardController@dashboardView')->name('admin.deshboard');

        Route::group(['prefix'=>'user'],function(){
            Route::get('registration/form','UserController@registrationForm')->name('admin.registration_form');
            Route::post('registration','UserController@registration')->name('admin.registration');

            Route::get('list','UserController@userList')->name('admin.user_list');
            Route::get('list/ajax','UserController@userListAjax')->name('admin.user_list_ajax');
        });

        Route::group(['prefix'=>'beat'],function(){
            Route::get('list','BeatController@beatList')->name('admin.beat_list');
            Route::get('list/ajax','BeatController@beatListAjax')->name('admin.beat_list_ajax');
        });

        Route::group(['prefix'=>'outlet'],function(){
            Route::get('list','OutletController@outletList')->name('admin.outlet_list');
            Route::get('list/ajax','OutletController@outletListAjax')->name('admin.outlet_list_ajax');
        });

        Route::group(['prefix'=>'vehicle'],function(){
            Route::get('list','VehicleController@vehicleList')->name('admin.vehicle_list');
            Route::get('list/ajax','VehicleController@vehicleListAjax')->name('admin.vehicle_list_ajax');
        });

        Route::group(['prefix'=>'report'],function(){
            Route::get('ended/delivery','ReportController@endedReport')->name('admin.ended_delivery_report');
            Route::get('ended/list/ajax','ReportController@endedReportAjax')->name('admin.ended_delivery_report_ajax');
        });

    });
});