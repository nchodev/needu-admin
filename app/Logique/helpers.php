<?php


namespace App\Logique;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Helpers
{

    public static function error_processor($validator)
    {
        $err_keeper=[];
        foreach($validator->errors()->getMessages() as $i => $error){
            array_push($err_keeper,['code' => $i, 'message' =>$error[0]]);
         }
         return $err_keeper;
    }

    public static function generate_refere_code(){

        $ref_code = strtoupper(Str::random(10));
        if(self::referer_code_exists($ref_code))
        {
            return self::generate_refere_code();
        }

    return $ref_code;
    }

    public static function referer_code_exists($refer_code){
        return User::where('ref_code',$refer_code)->exists();
    }
    public static function insert_business_settings_key($key, $value = null)
    {
        $data =  BusinessSetting::where('key', $key)->first();
        if (!$data) {
            DB::table('business_settings')->updateOrInsert(['key' => $key], [
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return true;
    }
    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('key', 'system_language')->first();
            \session()->put('language_settings', $language);
        }
        return $language;
    }
    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>'], ' ', $str);
    }
    public static function auto_translator($q, $sl, $tl)
    {
        $res = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $sl . "&tl=" . $tl . "&hl=hl&q=" . urlencode($q), $_SERVER['DOCUMENT_ROOT'] . "/transes.html");
        $res = json_decode($res);
        return str_replace('_',' ',$res[0][0][0]);
    }
    public static function upload(string $dir, string $format, $image = null)
    {
        if ($image != null) {
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->putFileAs($dir, $image, $imageName);
        } else {
            $imageName = 'default.png';
        }

        return $imageName;
    }
    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if ($image == null) {
            return $old_image;
        }
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = Helpers::upload($dir, $format, $image);
        return $imageName;
    }
    // public static function send_push_notif_to_device($fcm_token, $data, $web_push_link = null)
    // {

    //     $url = "http://192.168.1.2/api/v1/send-notification";


    //     if(isset($data['message'])){
    //         $message = $data['message'];
    //     }else{
    //         $message = '';
    //     }
    //     if(isset($data['conversation_id'])){
    //         $conversation_id = $data['conversation_id'];
    //     }else{
    //         $conversation_id = '';
    //     }
    //     if(isset($data['sender_type'])){
    //         $sender_type = $data['sender_type'];
    //     }else{
    //         $sender_type = '';
    //     }
    //     if(isset($data['module_id'])){
    //         $module_id = $data['module_id'];
    //     }else{
    //         $module_id = '';
    //     }
    //     if(isset($data['order_type'])){
    //         $order_type = $data['order_type'];
    //     }else{
    //         $order_type = '';
    //     }

    //     $click_action = "";
    //     if($web_push_link){
    //         $click_action = ',
    //         "click_action": "'.$web_push_link.'"';
    //     }

    //     $postdata = '{
    //         "device_token" : "' . $fcm_token . '",
    //         "mutable_content": true,
    //         "data" : {
    //             "title":"' . $data['title'] . '",
    //             "body" : "' . $data['description'] . '",
    //             "image" : "' . $data['image'] . '",
    //             "order_id":"' . $data['order_id'] . '",
    //             "type":"' . $data['type'] . '",
    //             "conversation_id":"' . $conversation_id . '",
    //             "sender_type":"' . $sender_type . '",
    //             "module_id":"' . $module_id . '",
    //             "order_type":"' . $order_type . '",
    //             "is_read": 0
    //         },
    //         "notification" : {
    //             "title" :"' . $data['title'] . '",
    //             "body" : "' . $data['description'] . '",
    //             "image" : "' . $data['image'] . '",
    //             "order_id":"' . $data['order_id'] . '",
    //             "title_loc_key":"' . $data['order_id'] . '",
    //             "body_loc_key":"' . $data['type'] . '",
    //             "type":"' . $data['type'] . '",
    //             "is_read": 0,
    //             "icon" : "new",
    //             "sound": "notification.wav",
    //             "android_channel_id": "NeedU"
    //             '.$click_action.'
    //         }
    //     }';
    //     $ch = curl_init();
    //     $timeout = 120;
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    //     // Get URL content
    //     $result = curl_exec($ch);
    //     // close handle to release resources
    //     curl_close($ch);

    //     return $result;
    // }


}
