<?php

namespace App\Http\Controllers\Admin;

use App\Logique\Helpers;
use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use App\Models\BusinessSetting;
use RecursiveDirectoryIterator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class LanguageController extends Controller
{


    public function languages(){


        return view('admin.languages.languages');
    }


    public function add(Request $request)
    {


        $language = BusinessSetting::where('key', 'system_language')->first();
        $lang_array = [];
        $codes = [];
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] != $request['code']) {
                if (!array_key_exists('default', $data)) {
                    $default = array('default' => ($data['code'] == 'en') ? true : false);
                    $data = array_merge($data, $default);
                }
                array_push($lang_array, $data);
                array_push($codes, $data['code']);
            }
        }
        array_push($codes, $request['code']);

        if (!file_exists(base_path('lang/' . $request['code']))) {
            mkdir(base_path('lang/' . $request['code']), 0777, true);
        }

        $lang_file = fopen(base_path('lang/' . $request['code'] . '/' . 'messages.php'), "w") or die("Unable to open file!");
        $read = file_get_contents(base_path('lang/en/messages.php'));
        fwrite($lang_file, $read);

        $lang_array[] = [
            'id' => $request['code'].count(json_decode($language['value'], true)) + 1,
            'code' => $request['code'],
            'direction' => $request['direction'],
            'status' => 0,
            'default' => false,
        ];

        BusinessSetting::updateOrInsert(['key' => 'system_language'], [
            'value' => $lang_array
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'language'], [
            'value' => json_encode($codes),
        ]);
        toastr()->success('Language Added!');
        return back();
    }

    public function update_status(Request $request)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();
        $lang_array = [];
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $request['code']) {
                $lang = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'] == 1 ? 0 : 1,
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            } else {
                $lang = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            }
        }
        $businessSetting = BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);

        return $businessSetting;
    }

    public function update_default_status(Request $request)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();
        $lang_array = [];
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $request['code']) {
                $lang = [
                    'id' => $data['id'],

                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => 1,
                    'default' => true,
                ];
                $lang_array[] = $lang;
            } else {
                $lang = [
                    'id' => $data['id'],

                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => false,
                ];
                $lang_array[] = $lang;
            }
        }
        BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);

        $direction = BusinessSetting::where('key', 'site_direction')->first();
        $direction = $direction->value ?? 'ltr';
        $language = BusinessSetting::where('key', 'system_language')->first();
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $request['code']) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('language_settings');
        Helpers::language_load();
        session()->put('local', $request['code']);
        session()->put('site_direction', $direction);
        toastr()->success('Default Language Changed!');

        return back();
    }
    public function update(Request $request)
    {


        $language = BusinessSetting::where('key', 'system_language')->first();
        $lang_array = [];
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $request['code']) {
                $lang = [
                    'id' => $data['id'],

                    'direction' => $request['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            } else {
                $lang = [
                    'id' => $data['id'],

                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            }
        }
        BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);
        Toastr::success('Language updated!');
        return back();
    }
    public function delete($lang)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();

        $del_default = false;
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $lang && array_key_exists('default', $data) && $data['default'] == true) {
                $del_default = true;
            }
        }

        $lang_array = [];
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] != $lang) {
                $lang_data = [
                    'id' => $data['id'],

                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => ($del_default == true && $data['code'] == 'en') ? 1 : $data['status'],
                    'default' => ($del_default == true && $data['code'] == 'en') ? true : (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                array_push($lang_array, $lang_data);
            }
        }

        BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);

        $dir = base_path('lang/' . $lang);
        if (File::isDirectory($dir)) {
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
        }


        $languages = array();
        $language = BusinessSetting::where('key', 'language')->first();
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data != $lang) {
                array_push($languages, $data);
            }
        }
        if (in_array('en', $languages)) {
            unset($languages[array_search('en', $languages)]);
        }
        array_unshift($languages, 'en');

        DB::table('business_settings')->updateOrInsert(['key' => 'language'], [
            'value' => json_encode($languages),
        ]);

        Toastr::success('Removed Successfully!');
        return back();
    }
    public function translate(Request $request,$lang)
    {
        $searchTerm =$request['search'];
        $full_data = include(base_path('lang/' . $lang . '/messages.php'));
        $full_data = array_filter($full_data, fn($value) => !is_null($value) && $value !== '');

        // If a search term is provided, filter the array based on the search term
        if (!empty($searchTerm)) {
            $full_data = array_filter($full_data, function ($value, $key) use ($searchTerm) {
                return (stripos($value, $searchTerm) !== false) || (stripos(ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key))), $searchTerm) !== false);
            }, ARRAY_FILTER_USE_BOTH);
        }


        ksort($full_data);
        $full_data = self::convertArrayToCollection($lang,$full_data,config('app.default_pagination'));


        // english folder data
        // $en_data = include(base_path('resources/lang/en/messages.php'));
        // $en_data = array_filter($en_data, fn($value) => !is_null($value) && $value !== '');
        // ksort($en_data);
        // $en_data = $this->convertArrayToCollection('en',$en_data,config('default_pagination'));

        // dd($en_data);

        return view('admin.languages.translate', compact('lang', 'full_data'));
    }

    private function convertArrayToCollection($lang, $items, $perPage = null, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $options = [
        "path" => route('language.translate',[$lang]),
        "pageName" => "page"
        ];
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    // private function convertArrayToCollection($lang, $items, $perPage = null, $page = null, $options = [])
    // {
    //     $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    //     $items = $items instanceof Collection ? $items : Collection::make($items);

    //     // Vérifie si $perPage est null ou égal à zéro
    //     if ($perPage === null || $perPage == 0) {
    //         // Retourner une collection vide si $perPage est null ou égal à zéro
    //         return new LengthAwarePaginator(Collection::make([]), 0, 1, $page, []);
    //     }

    //     $options = [
    //         "path" => route('language.translate',[$lang]),
    //         "pageName" => "page"
    //     ];

    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    // }
    public function translate_key_remove(Request $request, $lang)
    {
        $full_data = include(base_path('lang/' . $lang . '/messages.php'));
        unset($full_data[$request['key']]);
        $str = "<?php return " . var_export($full_data, true) . ";";
        file_put_contents(base_path('lang/' . $lang . '/messages.php'), $str);
    }

    public function translate_submit(Request $request, $lang)
    {
        $full_data = include(base_path('lang/' . $lang . '/messages.php'));
        $data_filtered = [];
        foreach ($full_data as $key => $data) {
            $data_filtered[$key] = $data;
        }
        $data_filtered[$request['key']] = $request['value'];
        $str = "<?php return " . var_export($data_filtered, true) . ";";
        file_put_contents(base_path('lang/' . $lang . '/messages.php'), $str);
        return back();
    }

    public function auto_translate(Request $request, $lang): \Illuminate\Http\JsonResponse
    {
        $lang_code = $lang;
        $full_data = include(base_path('lang/' . $lang . '/messages.php'));
        $data_filtered = [];

        foreach ($full_data as $key => $data) {
            $data_filtered[$key] = $data;
        }
        $translated=  str_replace('_', ' ', Helpers::remove_invalid_charcaters($request['key']));
        $translated = Helpers::auto_translator($translated, 'en', $lang_code);
        $data_filtered[$request['key']] = $translated;
        $str = "<?php return " . var_export($data_filtered, true) . ";";
        file_put_contents(base_path('lang/' . $lang . '/messages.php'), $str);

        return response()->json([
            'translated_data' => $translated
        ]);
    }
    public function lang($local)
    {

        $direction = BusinessSetting::where('key', 'site_direction')->first();
        $direction = isset($direction)? $direction->value : 'ltr';
        $language = BusinessSetting::where('key', 'system_language')->first();
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $local) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('language_settings');
        Helpers::language_load();
        App::setlocale($local);
        session()->put('local', $local);
        session()->put('site_direction', $direction);
        DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
            'value' => $direction
        ]);
        return redirect()->back();
    }

}
