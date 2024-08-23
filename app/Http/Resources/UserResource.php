<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nick_name' => $this->nick_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'dob' => (String) Carbon::parse($this->dob)->age,
            'bio' => $this->bio,
            'status' => $this->status,
            'coin_balance' => $this->coin_balance,
            'ref_code' => $this->ref_code,
            'current_lang' => $this->current_lang,
            'position' => json_decode($this->position, true),
            'location' => $this->location,
            'height' => $this->height,
            'education' => $this->education,
            'company' => $this->company,
            'profession' => $this->profession,
            'stories_count'=>$this->stories_count,
            'online'=>$this->online,
            'media_count'=>$this->media_count,
            'verified'=>$this->verified,
            'slug'=>$this->slug??Str::lower($this->nick_name),
            'looking_for' => $this->whenLoaded('lookingFor', function () {
                $preferenceAddonResource = new PreferenceAddonResource($this->lookingFor->preferenceAddon);
                // Exclure 'parent' de l'array retourné par toArray de PreferenceAddonResource
                return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
            }),
            'sex_orientation' =>  $this->whenLoaded('sexOrientation', function () {
                $preferenceAddonResource = new PreferenceAddonResource($this->sexOrientation->preferenceAddon);
                // Exclure 'parent' de l'array retourné par toArray de PreferenceAddonResource
                return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
            }),
            'gender' =>  $this->whenLoaded('gender', function () {
                $preferenceAddonResource = new PreferenceAddonResource($this->gender->preferenceAddon);
                // Exclure 'parent' de l'array retourné par toArray de PreferenceAddonResource
                return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
            }),
            'interests' =>self::formatData(PreferenceAddonResource::collection($this->interests->map(function ($interest) {
                return new PreferenceAddonResource($interest->preferenceAddon);
            }))),
            'spoken_languages' => self::formatData(PreferenceAddonResource::collection($this->spokenLanguages->pluck('preferenceAddon'))),

            'religion' =>$this->whenLoaded('religion', function () {
                $preferenceAddonResource = new PreferenceAddonResource($this->religion->preferenceAddon);
                // Exclure 'parent' de l'array retourné par toArray de PreferenceAddonResource
                return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
            }),
            'marital_status' => $this->whenLoaded('maritalStatus', function () {
                $preferenceAddonResource = new PreferenceAddonResource($this->maritalStatus->preferenceAddon);
                // Exclure 'parent' de l'array retourné par toArray de PreferenceAddonResource
                return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
            }),
            'more_abouts' => PreferenceAddonResource::collection($this->moreAbouts->pluck('preferenceAddon')),
            'life_styles' =>PreferenceAddonResource::collection($this->lifeStyles->pluck('preferenceAddon')),
            'media' => MediaResource::collection($this->media),
            // 'stories'=>$this->whenLoaded('stories', function () {
            //     return    StatusResource::collection($this->stories);
            // }),
            'preferences' => $this->whenLoaded('preferences', function () {
                return [

                        "sex_orientation_id" =>$this->preferences->sex_orientation_id,
                        "gender_id" => $this->preferences->gender_id,
                        "religion_id" => $this->preferences->religion_id,
                        "marital_status_id"=> $this->preferences->marital_status_id,
                        "looking_for_ids"=> $this->preferences->looking_for_ids,
                        "more_about_ids"=> $this->preferences->more_about_ids,
                        "life_styles"=> $this->preferences->life_styles,
                        "spoken_languages"=> $this->preferences->spoken_languages,
                        "min_age" => $this->preferences->min_age,
                        "max_age"=> $this->preferences->max_age,
                        "interests" => $this->preferences->interests,
                        "country"=> $this->preferences->country,
                        "max_distance"=>$this->preferences->max_distance
                ];
            }),



        ];
    }
    public  function formatData($data){

        $data= json_decode(json_encode($data), true);
        $reformattedInterests = array_map(function ($interest) {
            unset($interest['parent']);
            return $interest;
        }, $data);
        return $reformattedInterests;
    }
}
