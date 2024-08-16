<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\PaginatedStoryResource;
use App\Http\Resources\PaginatedSwipeResource;

class FetchUsersController extends Controller
{

public function filter_users(Request $request)
{
    $perPage = $request->input('limit', 10);
    $offset = $request->input('offset', 1);

    $currentUser = $request->user();

        $usersQuery = User::Active()
            ->notLikedUsers( $request->user()->id)
            ->where('id', '!=',  $request->user()->id);


    $preferences = $currentUser->preferences;

    if($preferences){
        $minDob = Carbon::now()->subYears($preferences->max_age)->toDateString();
        $maxDob = Carbon::now()->subYears($preferences->min_age)->toDateString();
    }else{
        $minDob = Carbon::now()->subYears(99)->toDateString();
        $maxDob = Carbon::now()->subYears(18)->toDateString();
    }


    // Appliquer les filtres selon les préférences
    if ($preferences) {
        if ($preferences->sex_orientation_id) {
            $usersQuery->whereHas('sexOrientation.preferenceAddon', function ($query) use ($preferences) {
                $query->where('id', $preferences->sex_orientation_id);
            });
        }
        if ($preferences->gender_id) {
            $usersQuery->whereHas('gender.preferenceAddon', function ($query) use ($preferences) {
                $query->where('id', $preferences->gender_id);
            });
        }
        if ($preferences->religion_id) {
            $usersQuery->whereHas('religion.preferenceAddon', function ($query) use ($preferences) {
                $query->where('id', $preferences->religion_id);
            });
        }
        if ($preferences->marital_status_id) {
            $usersQuery->whereHas('maritalStatus.preferenceAddon', function ($query) use ($preferences) {
                $query->where('id', $preferences->marital_status_id);
            });
        }
        if ($preferences->looking_for_ids) {

            $lookingForIds = (int)$preferences->looking_for_ids;

                $usersQuery->whereHas('lookingFor.preferenceAddon', function ($query) use ($lookingForIds) {
                    $query->where('id', $lookingForIds);
                });
        
        }
        if ($preferences->more_about_ids) {
            $moreAboutIds = json_decode($preferences->more_about_ids, true);

            if (!empty($moreAboutIds)) {
                $usersQuery->whereHas('moreAbouts', function ($query) use ($moreAboutIds) {
                    $query->whereIn('preference_addon_id', $moreAboutIds);
                });
            }
        }
        if ($preferences->life_styles) {
            $lifeStyle = json_decode($preferences->life_styles, true);

            if (!empty($lifeStyle)) {
                $usersQuery->whereHas('lifeStyles', function ($query) use ($lifeStyle) {
                    $query->whereIn('preference_addon_id', $lifeStyle);
                });
            }
        }
        if ($preferences->spoken_languages) {
            $langs = json_decode($preferences->spoken_languages, true);

            if (!empty($langs)) {
                $usersQuery->whereHas('spokenLanguages', function ($query) use ($langs) {
                    $query->whereIn('preference_addon_id', $langs);
                });
            }
        }

        if ($preferences->interests) {
            $interests = json_decode($preferences->interests, true);

            if (!empty($interests)) {
                $usersQuery->whereHas('interests', function ($query) use ($interests) {
                    $query->whereIn('preference_addon_id', $interests);
                });
            }
        }


        // if ($preferences->country) {
        //     $usersQuery->where('country', $preferences->country);
        // }

        if ($preferences->max_distance) {
            $currentUserPosition = json_decode($currentUser->position, true);

            if ($currentUserPosition && isset($currentUserPosition['latitude']) && isset($currentUserPosition['longitude'])) {
                $latitude = $currentUserPosition['latitude'];
                $longitude = $currentUserPosition['longitude'];
                $maxDistance = $preferences->max_distance * 1000; // Convertir en mètres

                $usersQuery->whereRaw("
                    ST_Distance_Sphere(
                        point(position->>'$.longitude', position->>'$.latitude'),
                        point(?, ?)
                    ) <= ?
                ", [$longitude, $latitude, $maxDistance]);
            }
        }
        $usersQuery->whereBetween('dob', [$minDob, $maxDob]);
    }

    // Pagination
    $users = $usersQuery->paginate($perPage, ['*'], 'page', $offset);

    // Convertir les données en tableau
    $data = new PaginatedSwipeResource($users);
    $dataArray = json_decode(json_encode($data), true);

    // Mélanger swipe_data
    if (isset($dataArray['swipe_data'])) {
        shuffle($dataArray['swipe_data']);
    }

    return $dataArray;
}




}
