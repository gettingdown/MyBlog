<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class PostController extends Controller
{
    public function createpost(Request $request){
        $request ->validate([
            'PostTheme'=>'required',
            'PostContent'=>'required'
        ]);
        $user = $request->user();
        $create = $request ->all();
        $post = new Post($create);
        $user -> posts()->save($post);
         $response=[
            'message'=>'Post created',
            'data'    => $post
        ];
         return response()->json($response, 201);
    }

         public function getpost(Request $request, $userid)
    {
        Validator::validate([
            'userid' => $userid
        ],[
            'userid' => 'integer|exists:users,id'
        ]);

        $user = User::all()->find($userid);
        $posts = $user->posts()->get();

        return response()->json($posts, 200);
    }
    public function mypost(Request $request)
    {
        $user = $request->user();
        $posts = $user->posts()->with("comments")->get();
        return response()->json($posts, 200);
    }



   public function deletePost(Request $request, $postid)
    {
        Validator::validate([
            'postid' => $postid
        ],[
            'postid' => 'integer|exists:posts,id',
        ]);

        $post = Post::all()->find($postid);
        $user = $request->user();

        if ($post->user()->isNot($user)) {
            $response = [
                'message' => 'You dont have permissions to delete this post'
            ];
            return response()->json($response, 403);
        }

        $post->delete();
        $response = [
            'message' => 'Post deleted'
        ];
        return response()->json($response, 200);
    }
}
