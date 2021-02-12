<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BlackList;
use App\Models\User;
use App\Models\Post;

class ChekBlackList
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
public function handle(Request $request, Closure $next, $entity)
    {
        $user_id = 0;

        if ($entity == 'user') {
            $user_id = $request->route('userid');
        }
        else if ($entity == 'post') {
            $postid = $request->route('postid');
            $post = Post::all()->firstWhere('id', $postid);
            $userid = $post->userid;
        }

        $blacklist = Blacklist::all()
            ->where('userid', $userid)
            ->firstWhere('userinlistID', $request->user()->id);

        if ($blacklist) {
            $response = [
                'message' => 'You in the blacklist'
            ];
            return response()->json($response, 403);
        }
        else {
            return $next($request);
        }
    }


}
