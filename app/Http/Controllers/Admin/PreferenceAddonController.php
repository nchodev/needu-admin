<?php

namespace App\Http\Controllers\Admin;

use App\Models\Translation;
use Illuminate\Http\Request;
use App\Models\PreferenceAddon;
use Yoeunes\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class PreferenceAddonController extends Controller
{
    public function bulk(){


        $languages = [
            "af" => "Afrikaans",
            "sq" => "Albanian",
            "am" => "Amharic",
            "ar" => "Arabic",
            "an" => "Aragonese",
            "hy" => "Armenian",
            "ast" => "Asturian",
            "az" => "Azerbaijani",
            "eu" => "Basque",
            "be" => "Belarusian",
            "bn" => "Bengali",
            "bs" => "Bosnian",
            "br" => "Breton",
            "bg" => "Bulgarian",
            "ca" => "Catalan",
            "ckb" => "Central Kurdish",
            "zh" => "Chinese",
            "co" => "Corsican",
            "hr" => "Croatian",
            "cs" => "Czech",
            "da" => "Danish",
            "nl" => "Dutch",
            "en" => "English",
            "eo" => "Esperanto",
            "et" => "Estonian",
            "fo" => "Faroese",
            "fil" => "Filipino",
            "fi" => "Finnish",
            "fr" => "French",
            "gl" => "Galician",
            "ka" => "Georgian",
            "de" => "German",
            "el" => "Greek",
            "gn" => "Guarani",
            "gu" => "Gujarati",
            "ha" => "Hausa",
            "haw" => "Hawaiian",
            "he" => "Hebrew",
            "hi" => "Hindi",
            "hu" => "Hungarian",
            "is" => "Icelandic",
            "id" => "Indonesian",
            "ia" => "Interlingua",
            "ga" => "Irish",
            "it" => "Italian",
            "ja" => "Japanese",
            "kn" => "Kannada",
            "kk" => "Kazakh",
            "km" => "Khmer",
            "ko" => "Korean",
            "ku" => "Kurdish",
            "ky" => "Kyrgyz",
            "lo" => "Lao",
            "la" => "Latin",
            "lv" => "Latvian",
            "ln" => "Lingala",
            "lt" => "Lithuanian",
            "mk" => "Macedonian",
            "ms" => "Malay",
            "ml" => "Malayalam",
            "mt" => "Maltese",
            "mr" => "Marathi",
            "mn" => "Mongolian",
            "ne" => "Nepali",
            "no" => "Norwegian",
            "nb" => "Norwegian Bokmål",
            "nn" => "Norwegian Nynorsk",
            "oc" => "Occitan",
            "or" => "Oriya",
            "om" => "Oromo",
            "ps" => "Pashto",
            "fa" => "Persian",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "pa" => "Punjabi",
            "qu" => "Quechua",
            "ro" => "Romanian",
            "mo" => "Romanian (Moldova)",
            "rm" => "Romansh",
            "ru" => "Russian",
            "gd" => "Scottish Gaelic",
            "sr" => "Serbian",
            "sh" => "Serbo-Croatian",
            "sn" => "Shona",
            "sd" => "Sindhi",
            "si" => "Sinhala",
            "sk" => "Slovak",
            "sl" => "Slovenian",
            "so" => "Somali",
            "st" => "Southern Sotho",
            "es" => "Spanish",
            "su" => "Sundanese",
            "sw" => "Swahili",
            "sv" => "Swedish",
            "tg" => "Tajik",
            "ta" => "Tamil",
            "tt" => "Tatar",
            "te" => "Telugu",
            "th" => "Thai",
            "ti" => "Tigrinya",
            "to" => "Tongan",
            "tr" => "Turkish",
            "tk" => "Turkmen",
            "tw" => "Twi",
            "uk" => "Ukrainian",
            "ur" => "Urdu",
            "ug" => "Uyghur",
            "uz" => "Uzbek",
            "vi" => "Vietnamese",
            "wa" => "Walloon",
            "cy" => "Welsh",
            "fy" => "Western Frisian",
            "xh" => "Xhosa",
            "yi" => "Yiddish",
            "yo" => "Yoruba",
            "zu" => "Zulu"
        ];

        foreach ($languages as $code => $name) {
            // Vérifier si la langue existe déjà en base de données pour éviter les doublons
            $existingLanguage = PreferenceAddon::where('name', $name)->first();

            if (!$existingLanguage) {
                // Créer une nouvelle entrée si la langue n'existe pas encore
                PreferenceAddon::create([
                    'name' => $name,
                    'position' => 1, // Exemple de valeur pour la position
                    'parent_id' => 13, // Exemple de valeur pour l'ID parent
                ]);
            }
        }
        return 'ok';

    }
    public function index(Request $request, $position)
    {
        $cats =null;
        if($position!=0){
            $cats = PreferenceAddon::where('parent_id',0)->get();
            $key = explode(' ', $request['search']);
            $addons=PreferenceAddon::with('parent')->where('position',$position)->when(isset($key) , function ($q) use($key){
                    $q->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%");
                        }
                    });
                })
            ->latest()->paginate(config('app.default_pagination'))->appends($position);

        }else{
            $key = explode(' ', $request['search']);
            $addons=PreferenceAddon::with('parent')->where('position',$position)->when(isset($key) , function ($q) use($key){
                    $q->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%");
                        }
                    });
                })
            ->latest()->paginate(config('app.default_pagination'))->appends($position);

        }

// return $addons;

        return view('admin.preference-addon.index',compact('addons','cats','position'));
    }
    public function store(Request $request, $position)
    {
        $request->validate([
            'name' => 'required|max:100',
            'name.0' => 'required',
        ], [
            'name.required' => translate('messages.Name is required!'),
            'name.0.required'=>translate('default_name_is_required'),
        ]);


        $addons = new PreferenceAddon();
        $addons->name = $request->name[array_search('default', $request->lang)];
        $addons->position= $position;
        $addons->parent_id= $request->parent2_id? $request->parent2_id: ($request->parent_id==null? 0 : $request->parent_id) ;
        $addons->save();
        $default_lang = str_replace('_', '-', App::getLocale());
        $data = [];
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if($key != 'default')
                {
                    array_push($data, Array(
                        'translationable_type'  => 'App\Models\PreferenceAddon',
                        'translationable_id'    => $addons->id,
                        'locale'                => $key,
                        'key'                   => 'name',
                        'value'                 => $addons->name,
                    ));
                }
            }else{

                if($request->name[$index] && $key != 'default')
                {
                    array_push($data, Array(
                        'translationable_type'  => 'App\Models\PreferenceAddon',
                        'translationable_id'    => $addons->id,
                        'locale'                => $key,
                        'key'                   => 'name',
                        'value'                 => $request->name[$index],
                    ));
                }
            }
        }
        if(count($data))
        {
            Translation::insert($data);
        }

        Toastr::success(translate('messages.data_added_successfully'));
        return back();
    }

    public function edit($id, $position)
    {
        $addon = PreferenceAddon::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin.preference-addon.edit', compact('addon','position'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|array',
            'name.0'=>'unique:preference_addons,name,'.$id,
            'name.*'=>'max:191',
            'name.0' => 'required',
        ],[
            'name.0.required'=>translate('default_name_is_required'),
        ]);

        $addon = PreferenceAddon::findOrFail($id);
        $addon->name = $request->name[array_search('default', $request->lang)];
        $addon->save();

        $default_lang = str_replace('_', '-', App::getLocale());

        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\PreferenceAddon',
                            'translationable_id' => $addon->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $addon->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\PreferenceAddon',
                            'translationable_id' => $addon->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
            }

        }

        Toastr::success(translate('messages.data_updated_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        $looking = PreferenceAddon::find($request->id);
        $looking->status = $request->status;
        $looking->save();
        Toastr::success(translate('messages.status_updated'));
        return back();
    }
    public function destroy($id)
    {
        $addon = PreferenceAddon::findOrFail($id);

        $addon->translations()->delete();
        $addon->delete();
        Toastr::success(translate('messages.data_deleted_successfully'));
        return back();
    }
    public function fetch(Request $request)
    {
        $data = PreferenceAddon::where('parent_id', $request->id)->get();
        $output='<option label="Choose one"></option>';

        foreach($data as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        return response()->json($output);


    }


}

