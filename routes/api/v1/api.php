<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\V1'], function(){
    Route::group(['prefix'=>'auth','namespace'=>'Auth'], function(){
        Route::Post('/social-login','SocialAuthController@social_login');
    });

    // Route::get('filter-users', 'FetchUsersController@filter_users');
    // Route::get('user-info', 'UserController@info');
    Route::get('matches', 'MatchController@get_matches');
    Route::post('like-user', 'MatchController@like_user');
    Route::get('gifts', 'GiftController@get_gift');
    Route::group(['prefix'=>'chat','as'=>'chat.' ], function(){
        Route::post('send','ChatController@send');
        Route::get('get-user-chats','ChatController@get_user_chats');

    });



        Route::get('looking-for','FetchPreferencesController@lookingFor');
        Route::get('sex-orientation','FetchPreferencesController@sexOrientation');
        Route::get('interest','FetchPreferencesController@interest');
        Route::get('spoken-languages','FetchPreferencesController@spoken_languages');
        Route::get('religions','FetchPreferencesController@religions');
        Route::get('marital-statuses','FetchPreferencesController@marital_statuses');
        Route::get('more-about','FetchPreferencesController@more_about');
        Route::get('life-style','FetchPreferencesController@life_style');


    Route::group(['prefix' => 'customer', 'as'=>'customer.', 'middleware' => 'auth:api'], function () {
        Route::post('register', 'UserController@user_register');
        Route::post('address', 'UserController@address');
        Route::put('cm-firebase-token', 'UserController@update_cm_firebase_token');
        Route::get('user-info', 'UserController@info');
        Route::post('update-images', 'UserController@update_images');
        Route::post('delated-image', 'UserController@delate_image');
        Route::post('change-image', 'UserController@change_image');
        Route::post('update-profile', 'UserController@update_profile');
        Route::get('filter-users', 'FetchUsersController@filter_users');
        Route::post('like-user', 'MatchController@like_user');
        Route::get('matches', 'MatchController@get_matches');
        Route::get('gifts', 'GiftController@get_gift');
        Route::group(['prefix'=>'chat','as'=>'chat.' ], function(){
            Route::post('send','ChatController@send');
            Route::get('get-user-chats','ChatController@get_user_chats');

        });

    });



});

