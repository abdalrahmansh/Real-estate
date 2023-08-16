<?php

namespace App\Http\Controllers;

use App\Models\PostUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function allPosts()
    {
        $allPosts = PostUser::with('user', 'operation', 'postsable', 'postsable.images')
            ->orderBy('counter', 'desc')
            ->get();

        return response()->json($allPosts);
    }

    public function postsNeedReview(Request $request)
    {
        $estate = $this->getModel($request->estate);
        $allPosts = PostUser::with('user', 'operation', 'postsable', 'postsable.images')
            ->where('post_user.is_accepted',0)
            ->whereHas('postsable', function ($query) use ($estate) {
                $query->where('postsable_type', $estate);
            })
            ->orderBy('counter', 'desc')
            ->get();

        return response()->json($allPosts);
    }

    public function postsNeedReviewToReserving()
    {
        $allPosts = PostUser::with('user', 'operation', 'postsable', 'postsable.images')
            ->where('post_user.operation_id',4)
            ->orderBy('counter', 'desc')
            ->get();

        return response()->json($allPosts);
    }

    public function acceptedPosts(Request $request)
    {
        $estate = $this->getModel($request->estate);
        $acceptedRecords = PostUser::where('is_accepted', 1)
            ->with('user', 'operation', 'postsable', 'postsable.images')
            ->whereHas('postsable', function ($query) use ($estate) {
                $query->where('postsable_type', $estate);
            })
            ->orderBy('counter', 'desc')
            ->get();
        return response()->json($acceptedRecords);
    }

    public function aUserPosts(Request $request)
    {
        $estate = $this->getModel($request->estate);
        $posts = PostUser::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->whereHas('post.postsable', function ($query) use ($estate) {
                $query->where('postsable_type', $estate);
            })
            ->orderBy('counter', 'desc')
            ->get();
        // $user = \Illuminate\Support\Facades\Auth::user();
        return response()->json($posts);
    }

    public function showWithoutCounter($post)
    {
        $post = PostUser::with('user', 'operation', 'postsable', 'postsable.images')
        ->findOrFail($post);
        
        return response()->json($post);
    }
    
    public function show($post)
    {
        $post = PostUser::with('user', 'operation', 'postsable', 'postsable.images')
        ->findOrFail($post);

        // Increment the counter by 1
        $post->counter = $post->counter + 1;
        $post->save();
        
        return response()->json($post);
    }

    public function destroy($post)
    {
        $postUsers = PostUser::where('id', $post)
            ->with('user', 'operation', 'postsable', 'postsable.images')
            ->get();

        foreach ($postUsers as $postUser) {
            $postsable = $postUser->postsable;

            if ($postsable) {
                $images = $postsable->images;

                if ($images) {
                    foreach ($images as $image) {
                        if ($image->img) {
                            $urlParts = parse_url($image->img);
                            if (isset($urlParts['path'])) {
                                $filePath = ltrim($urlParts['path'], '/');
                                Storage::delete($filePath);
                            }                        
                        }
                    }

                    // Delete related images from database
                    $postsable->images()->delete();
                }

                // Delete the postable 
                $postsable->delete();
            }

            // Delete the PostUser record
            $postUser->delete();
        }

        return response()->json(['message' => 'Posts and all related records have been deleted.']);
    }

    public function accept($post)
    {
        $post = PostUser::find($post);
        $post->update(['is_accepted' => 1]);
        // Send a notification to the author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'accepted'));

        return response()->json(['message' => 'Post accepted successfully']);
    }
    public function reject($post)
    {
        $post = PostUser::find($post);
        $post->update(['is_accepted' => -1]);    
        // Send a notification to the post author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'rejected'));
    
        return response()->json(['message' => 'Post rejected successfully']);
    }

    public function acceptReserve($post)
    {
        $post = PostUser::find($post);
        $post->update(['is_reserved' => 1]);
        // Send a notification to the author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'accepted'));

        return response()->json(['message' => 'Post accepted successfully']);
    }

    public function rejectReserve($post)
    {
        $post = PostUser::find($post);
        $post->update(['is_reserved' => -1]);
    
        // Send a notification to the post author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'rejected'));
    
        return response()->json(['message' => 'Post rejected successfully']);
    }

    public function reserve($post)
    {
        $user = Auth::user(); // or however you get the ID of the current user
        $existingReservation = PostUser::where('id', $post)->where('user_id', $user->id)->where('operation_id', 4)->first();
        if($existingReservation != null){
            return response()->json(['message' => 'You have already reserved this post']);
        }
        $post = PostUser::find($post);
        $newPost = $post->replicate();
        $newPost->operation_id = 4;
        $newPost->is_accepted = 0;
        $newPost->user_id = $user->id;
        $newPost->save();
        return response()->json(['message' => 'Post reserved successfully']);
    }

    protected function getModel(string $modelType): string
    {
        $className = "App\\Models\\" . ucfirst(class_basename($modelType));

        return class_exists($className) ? $className : '';
    }
}
