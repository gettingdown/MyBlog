<?php

namespace App\Http\Controllers;
use App\Models\BlackList;
use App\Models\User;
use Illuminate\Http\Request;

class BlackListController extends Controller
{
    public function blacklistuser(Request $request, $userid)
    {
        Validator::validate([
            'userid' => $userid
        ],[
            'userid' => [
                'integer', 'exists:users,id',
                Rule::notIn($request->user()->id)],
        ]);

        $userinlist = $userid;
        $userid = $request->user()->id;


        $blacklist = Blacklist::all()
            ->where('user_id', $userid)
            ->firstWhere('userinlistID', $userinlist);

        if ($blacklist) {
            $blacklist->delete();
            $response = [
                'message' => 'you removed from the blacklist'
            ];
            return response()->json($response, 200);
        }

        $blacklist = new Blacklist([
            'userid' => $userid,
            'userinlistID' => $userinlist,
        ]);
        $blacklist->save();

        $response = [
            'message' => 'You added to black list'
        ];
        return response()->json($response, 200);
    }
    public function getBlacklist(Request $request)
    {
        $user = $request->user();
        $blacklist = $user->blacklist()->get();
        return response()->json($blacklist);
    }
}
