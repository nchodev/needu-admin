<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
   {
        $lang ='en';
        $direction ='ltr';
        try {
            $language = BusinessSetting::where('key', 'system_language')->first();
            if($language){
                foreach (json_decode($language->value, true) as $key => $data) {
                    if ($data['default']) {
                        $lang= $data['code'];
                        $direction= $data['direction'];
                    }
                }
            }
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }

            if (session()->has('local')) {
                App::setLocale(session()->get('local'));
            }
            else{
                session()->put('site_direction', $direction);
                App::setLocale($lang);
            }
     
        return $next($request);
    }
}
