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
        $allPosts = PostUser::with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->get();

        return response()->json($allPosts);
    }

    public function postsNeedReview()
    {
        $allPosts = PostUser::with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->where('post_user.is_accepted',0)
            ->get();

        return response()->json($allPosts);
    }

    public function postsNeedReviewToReserving()
    {
        $allPosts = PostUser::with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->where('post_user.operation_id',5)
            ->get();

        return response()->json($allPosts);
    }

    public function acceptedPosts(Request $request)
    {
        $estate = $this->getModel($request->estate);
        $acceptedRecords = PostUser::where('is_accepted', 1)
            // ->where('operation_id', 1)
            ->with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->whereHas('post.postsable', function ($query) use ($estate) {
                $query->where('postsable_type', $estate);
            })->get();
        return response()->json($acceptedRecords);
    }

    public function aUserPosts()
    {
        $posts = PostUser::where('user_id', \Illuminate\Support\Facades\Auth::id())
            // ->where('operation_id', 1)
            ->with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->get();
        // $user = \Illuminate\Support\Facades\Auth::user();
        return response()->json($posts);
    }

    public function show($post)
    {
        $post = PostUser::where('post_id', $post)
            // ->where('operation_id', 1)
            ->with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->get();

        return response()->json($post);
    }

    public function destroy($post)
    {
        $post_user = PostUser::where('post_id', $post)
            ->with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->get();

        // if(empty($post_user)){
            // return response()->json($post_user, 404);
        // }
        // loop through each post
        foreach ($post_user as $post) {
            // delete the related images from the storage
            foreach ($post->post->postsable->images as $image) {
                Storage::delete($image->path);
            }
            // delete the related images
            $post->post->postsable->images()->delete();

            // delete the post
            $post->post->delete();

            // delete the post-user relationship
            $post->delete();
        }

            return response()->json(['message' => 'Post and all related records have been deleted.']);
            // return response()->json(['error' => 'Nothing deleted']);
        // return response()->json(['message' => 'post deleted successfully'], 200);
    }

    public function accept($post, $user)
    {
        User::find($user)
        ->posts()
        ->wherePivot('user_id', $user)
        ->wherePivot('post_id', $post)
        ->update(['is_accepted' => 1]);
        $post = PostUser::find($post);
        // Send a notification to the author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'accepted'));

        return response()->json(['message' => 'Post accepted successfully']);
    }
    public function reject($post, $user)
    {
        User::find($user)
        ->posts()
        ->wherePivot('user_id', $user)
        ->wherePivot('post_id', $post)
        ->update(['is_accepted' => -1]);
        $post = PostUser::find($post);
    
        // Send a notification to the post author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'rejected'));
    
        return response()->json(['message' => 'Post rejected successfully']);
    }

    public function acceptReserve($post, $user)
    {
        User::find($user)
        ->posts()
        ->wherePivot('user_id', $user)
        ->wherePivot('post_id', $post)
        ->update(['is_accepted' => 1]);
        $post = PostUser::find($post);
        // Send a notification to the author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'accepted'));

        return response()->json(['message' => 'Post accepted successfully']);
    }

    public function rejectReserve($post, $user)
    {
        User::find($user)
        ->posts()
        ->wherePivot('user_id', $user)
        ->wherePivot('post_id', $post)
        ->update(['is_accepted' => -1]);
        $post = PostUser::find($post);
    
        // Send a notification to the post author
        $post->user->notify(new \App\Notifications\PostStatusNotification($post, 'rejected'));
    
        return response()->json(['message' => 'Post rejected successfully']);
    }

    public function reserve($post)
    {
        $user = Auth::user(); // or however you get the ID of the current user
        $existingReservation = PostUser::where('post_id', $post)->where('user_id', $user->id)->where('operation_id', 5)->first();
        if($existingReservation != null){
            return response()->json(['message' => 'You have already reserved this post']);
        }
        $post = PostUser::where('post_id', $post)->first();
        $newPost = $post->replicate();
        $newPost->operation_id = 5;
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
