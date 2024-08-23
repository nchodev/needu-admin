<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Story;
use App\Logique\Helpers;
use App\Models\UserMatch;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\StatusResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserMatchResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaginatedStoryResource;
use App\Http\Resources\UserMatchStoryResource;

class UserStatusController extends Controller
{

    public function add(Request $request)
    {
        // $files = $request->allFiles();
        // foreach ($files as $key => $file) {
        //     Log::info("Fichier reçu: $key, Taille: " . $file->getSize());
        // }


        $validator = Validator::make($request->all(), [
            'type' => 'required|in:text,video,image',
            'mood' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        // Vérification de la présence de fichiers
        if (!$request->hasFile('image') && (!$request->hasFile('video') || !$request->hasFile('cover'))) {
            return response()->json(['message' => translate('messages.operation_failed')], 403);
        }
     try {
            // Commencer une transaction
            DB::beginTransaction();
            $storyIds = [];

            // Gestion des images
            if ($request->hasFile('image')) {
               $image = $request->file('image');
                $imageName = Helpers::upload('stories/', 'png', $image);

                $story = Story::create([
                    'mood_id' => $request->mood,
                    'user_id' => $request->user()->id,
                    'content' => $imageName,
                    'type' => 'image'
                ]);
                $storyIds[] = $story->id;

            }

            // Gestion des vidéos
            if ($request->hasFile('video')  && $request->hasFile('cover')) {
                $video= $request->file('video');
                $videoName = Helpers::upload('stories/', 'mp4', $video);
                $cover = $request->file('cover');
                $coverName = Helpers::upload('stories/', 'png', $cover);
                $story = Story::create([
                    'mood_id' => $request->mood,
                    'user_id' => $request->user()->id,
                    'content' => $videoName,
                    'thumbnail'=>$coverName,
                    'type' => 'video'
                ]);
                $storyIds[] = $story->id;

          }
        // Valider la transaction
        DB::commit();
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Enregistrer l'erreur dans les logs pour diagnostic
            Log::error('Error adding story: ' . $e->getMessage());

            return response()->json(['message' => translate('an_error_occurred_while_adding_the_status')], 500);
        }
        // Récupération des histoires ajoutées depuis l'utilisateur
        $user = User::with(['stories' => function ($query) use ($storyIds) {
            $query->whereIn('id', $storyIds);
        }])->withCount('stories')->find($request->user()->id);

        // Transformation des données en ressources
        // $data = StatusResource::collection($user->stories);



        // return $user->stories;

        $data = new StatusResource($user->stories[0]);

        return response()->json([
            'message' => translate('status_send_successfully!'),
            'data' => $data
        ]);
    }

    public function delete(Request $request)
    {
        try {
            // Commencer une transaction
            DB::beginTransaction();

            // Récupérer la story à supprimer
            $story = Story::where(['id' => $request->id, 'user_id' => $request->user()->id])->first();

            if (!$story) {
                return response()->json(['message' => translate('status_not_found')], 404);
            }
            if($story->thumbnail){
                Storage::disk('public')->delete('stories/' . $story->thumbnail);
            }
            // Supprimer le fichier associé à la story
            Storage::disk('public')->delete('stories/' . $story->content);

            // Supprimer la story de la base de données
            $story->delete();

            // Valider la transaction
            DB::commit();

            return response()->json(['message' => translate('status_deleted_successfully!')]);
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Enregistrer l'erreur dans les logs pour diagnostic
            Log::error('Error deleting story: ' . $e->getMessage());

            return response()->json(['message' => translate('an_error_occurred_while_deleting_the_status')], 500);
        }
    }


    public function all_user_statuses(Request $request)
    {
        $perPage = $request->input('limit', 10);
        $offset = $request->input('offset', 1);

        // Sélectionner les utilisateurs actifs avec un comptage des stories actives uniquement
        $users = User::active()
            ->with(['stories' => function ($query) {
                $query->active()->orderBy('created_at', 'desc'); // Assurez-vous que les stories sont actives et triées par date
            }])
            ->whereHas('stories', function ($query) use ($request) {
                $query->active()->where('user_id', '!=', $request->user()->id); // Exclure les stories de l'utilisateur avec l'ID
            })
            ->withCount(['stories as active_stories_count' => function ($query) {
                $query->active(); // Compter seulement les stories actives
            }])
            ->paginate($perPage, ['*'], 'page', $offset);


        $data = new PaginatedStoryResource($users);

        // Convertir les données en tableau
        $dataArray = json_decode(json_encode($data), true);

        // Mélanger stories_data
        if (isset($dataArray['stories_data'])) {
            shuffle($dataArray['stories_data']);
        }

        return $dataArray;
    }


    public function read(Request $request)
    {
        $story = Story::where('id', $request->id)->first();

        if ($story) {
            $story->increment('read_count');
            return response()->json(['message' => 'read successfully'], 200);
        } else {
            return response()->json(['message' => 'Story not found'], 404);
        }
    }
    public function getUsersWithActiveStories()
    {
        $users = User::whereHas('stories', function ($query) {
            $query->active();
        })->get();

        return UserResource::collection($users);
    }
    public function get_matche_stories(Request $request)
    {

        $userId = $request->user()->id;

        $matches = UserMatch::where(function($query) use ($userId) {
            $query->where('user1_id', $userId)
                ->orWhere('user2_id', $userId);
        })

        ->with(['user1.activeStories', 'user2.activeStories'])
        ->get();

                // Extraire les utilisateurs des matchs et éliminer ceux qui n'ont pas de stories actives
                $users = $matches->map(function($match) use ($userId) {
                    return $match->user1_id == $userId ? $match->user2 : $match->user1;
                })
                ->filter(function($user) {
                    return $user->activeStories->isNotEmpty();
                });
                return UserMatchStoryResource::collection($users) ;



    }

}
