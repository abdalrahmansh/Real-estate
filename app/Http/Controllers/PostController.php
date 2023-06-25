<?php

namespace App\Http\Controllers;

use App\Models\PostUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $allPosts = PostUser::with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->get();

        return response()->json($allPosts);
    }

    public function acceptedPosts(Request $request)
    {
        $estate = $this->getModel($request->estate);
        $acceptedRecords = PostUser::where('is_accepted', 1)->where('operation_id', 1)
            ->with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->whereHas('post.postsable', function ($query) use ($estate) {
                $query->where('postsable_type', $estate);
            })->get();
        return response()->json($acceptedRecords);
    }

    public function show($post)
    {
        $post = PostUser::where('post_id', $post)->where('operation_id', 1)
            ->with('user', 'post', 'operation', 'post.postsable', 'post.postsable.images')
            ->get();

        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($request->all());

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

        return response()->json(['message' => 'Post accepted successfully']);
    }

    protected function getModel(string $modelType): string
    {
        $className = "App\\Models\\" . ucfirst(class_basename($modelType));

        return class_exists($className) ? $className : '';
    }
}
