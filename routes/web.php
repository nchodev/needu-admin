<?php

use App\Http\Middleware\AdminAuth;
use App\Models\Admin;
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'Admin'], function(){
    Route::get('/login','AuthController@show')->withoutMiddleware([AdminAuth::class]);
    Route::post('login','AuthController@login')->name('login')->withoutMiddleware([AdminAuth::class]);
    Route::get('/bulk','PreferenceAddonController@bulk');
    Route::get('/','DashboardController@index')->name('dashboard');
    Route::get('/business-setting','BusinessManagmentController@business_settings')->name('business-setting');
    Route::group(['prefix' => 'language', 'as' => 'language.'], function () {
        Route::get('/language-setting','LanguageController@languages')->name('language-setting');
        Route::post('/add-language','LanguageController@add')->name('add-language');
        Route::post('/update-language','LanguageController@update')->name('update-language');
        Route::delete('delete-language/{lang}', 'LanguageController@delete')->name('delete-language');
        Route::get('update-status', 'LanguageController@update_status')->name('update-status');
        Route::get('update-default-status', 'LanguageController@update_default_status')->name('update-default-status');
        Route::get('translate/{lang}', 'LanguageController@translate')->name('translate');
        Route::get('translate-submit/{lang}', 'LanguageController@translate_submit')->name('translate-submit');
        // Route::post('remove-key/{lang}', 'LanguageController@translate_key_remove')->name('remove-key');
        Route::any('auto-translate/{lang}', 'LanguageController@auto_translate')->name('auto-translate');
        Route::get('lang/{lang}', 'LanguageController@lang')->name('lang');

    });
    Route::group(['prefix'=>'customer', 'as' =>'customer.'],function(){
        Route::get('list/{type}','CustomerController@index')->name('index');
        Route::get('/update-status/{id}/{status}', 'CustomerController@status')->name('update-status');
        Route::get('/add', 'CustomerController@add')->name('add');
        // Route::post('/add', 'CustomerController@store')->name('store');

    });

    Route::group(['prefix'=>'preference-addon', 'as' =>'preference-addon.'],function(){
        Route::get('/index/{position}','PreferenceAddonController@index')->name('index');
        Route::post('/add-addon-category/{position}','PreferenceAddonController@store')->name('add-addon-category');
        Route::get('/edit-addon-category/{id}/{position}','PreferenceAddonController@edit')->name('edit-addon-category');
        Route::post('/addon-update/{id}','PreferenceAddonController@update')->name('addon-update');
        Route::post('/get-addon-category','PreferenceAddonController@fetch')->name('get-addon-category');
        Route::get('/update-status/{id}/{status}','PreferenceAddonController@status')->name('update-status');
        Route::delete('/delete/{id}','PreferenceAddonController@destroy')->name('delete');
    });

    Route::group(['prefix'=>'gifts', 'as'=>'gifts.'], function(){
        Route::get('/','BusinessManagmentController@gift_index')->name('index');
        Route::post('/store','BusinessManagmentController@gift_store')->name('store');
        Route::get('/update-status/{id}/{status}','BusinessManagmentController@status')->name('update-status');
        Route::delete('/delete/{id}','BusinessManagmentController@destroy')->name('delete');
        Route::get('/edit/{id}','BusinessManagmentController@edit')->name('edit');
        Route::post('/update','BusinessManagmentController@update')->name('update');

    });


});
