<?php

namespace App\Providers;

use App\Logique\Helpers;
use Laravel\Passport\Passport;
use App\Models\BusinessSetting;
use App\Services\LanguageService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Paginator::useBootstrap();

        try {
            if (Schema::hasTable('business_settings')) {
                //language
                $language = BusinessSetting::where('key', 'system_language')->exists();
                if(!$language){
                    Helpers::insert_business_settings_key('system_language','[{"id":1,"direction":"ltr","code":"en","status":1,"default":true}]');
                }
                $language = BusinessSetting::where('key', 'system_language')->first();
                $language = json_decode($language['value'],true);
                $languages= new LanguageService();
                $langs = $languages->getLanguages();
                View::share(['locales' => $language,'language'=>$langs]);
                Schema::defaultStringLength(191);
            }

        }catch (\Exception $exception){

        }
    }
}
