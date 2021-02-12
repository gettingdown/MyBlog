<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
public function getcomments(Request $request, $postid)
    {
        Validator::validate([
            'postid' => $postid
        ],[
            'postid' => 'exists:posts,id|integer',
        ]);

        $post = Post::all()->find($postid);
        $comments = $post->comments()->get();

        return response()->json($comments, 200);
    }
    public function createcomment(Request $request, $postid)
    {
        Validator::validate([
            'postid' => $postid
        ],[
            'postid' => 'exists:posts,id|integer',
        ]);

        $request->validate([
            'text' => 'required',
        ]);

        $comment = new Comment($request->all());
        $user = $request->user();
        $comment->user()->associate($user);
        $post = Post::all()->find($postid);
        $comment->post()->associate($post);
        $comment->save();

        $response = [
            'message' => 'Comment created ',
            'data'    => $comment,
        ];
        return response()->json($response, 201);
    }
    public function deletecomment(Request $request, $commentid)
    {
        Validator::validate([
            'commentid' => $commentid
        ],[
            'commentid' => 'exists:comments,id|integer',
        ]);

        $comment = Comment::all()->find($commentid);
        $user = $request->user();

        if ($comment->user()->isNot($user)) {
            $response = [
                'message' => 'You in the blacklist!'
            ];
            return response()->json($response, 403);
        }

        $comment->delete();
        $response = [
            'message' => 'Comment deleted '
        ];
        return response()->json($response, 200);
    }

}
