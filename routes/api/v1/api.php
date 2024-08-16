<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\V1'], function(){
    Route::group(['prefix'=>'auth','namespace'=>'Auth'], function(){
        Route::Post('/social-login','SocialAuthController@social_login');
    });


        Route::get('looking-for','FetchPreferencesController@lookingFor');
        Route::get('sex-orientation','FetchPreferencesController@sexOrientation');
        Route::get('interest','FetchPreferencesController@interest');
        Route::get('spoken-languages','FetchPreferencesController@spoken_languages');
        Route::get('religions','FetchPreferencesController@religions');
        Route::get('marital-statuses','FetchPreferencesController@marital_statuses');
        Route::get('more-about','FetchPreferencesController@more_about');
        Route::get('life-style','FetchPreferencesController@life_style');
        Route::get('status-mood','FetchPreferencesController@status_mood');
        Route::get('user-gender','FetchPreferencesController@gender');
        Route::get('config-data','ConfigController@configuration');


    Route::group(['prefix' => 'customer', 'as'=>'customer.', 'middleware' => 'auth:api'], function () {
        Route::post('register', 'UserController@user_register');
        Route::post('address', 'UserController@address');
        Route::put('cm-firebase-token', 'UserController@update_cm_firebase_token');
        Route::get('user-info/{id}', 'UserController@info');
        Route::post('update-images', 'UserController@update_images');
        Route::post('delated-image', 'UserController@delate_image');
        Route::post('change-image', 'UserController@change_image');
        Route::post('update-profile', 'UserController@update_profile');
        Route::get('filter-users', 'FetchUsersController@filter_users');
        Route::post('update-user-pref', 'UserController@update_user_pref');


        Route::post('like-user', 'MatchController@like_user');
        Route::post('unlike-user', 'MatchController@unlike_user');
        Route::get('liked-user', 'MatchController@liked_user');
        Route::post('match-request', 'MatchController@match_request');
        Route::get('gifts', 'GiftController@get_gift');
        Route::group(['prefix'=>'chat','as'=>'chat.' ], function(){
            Route::post('send','ChatController@send');
            Route::get('get-user-chats','ChatController@get_user_chats');
            Route::get('get-all-chats','ChatController@get_all_chats');
            Route::get('match-stories/', 'UserStatusController@get_matche_stories');

        });
        Route::group(['prefix'=>'status','as'=>'status.' ], function(){
            Route::post('add', 'UserStatusController@add');
            Route::get('all-user-statuses', 'UserStatusController@all_user_statuses');
            Route::post('delete-status', 'UserStatusController@delete');
            Route::post('read', 'UserStatusController@read');
        });

    });


});

