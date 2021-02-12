<?php

namespace App\Http\Controllers;
use App\Models\Subscription;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribetouser(Request $request, $user_id)
    {
        Validator::validate([
            'user_id' => $user_id
        ],[
            'user_id' => [
                'integer', 'exists:users,id',
                Rule::notIn($request->user()->id)],
        ]);

        $subid = $user_id;
        $user_id = $request->user()->id;

        $subscription = Subscription::all()
            ->where('user_id', $user_id)
            ->firstWhere('subID', $subid);

        if ($subscription) {
            $subscription->delete();
            $response = [
                'message' => 'You unsubscribed '
            ];
            return response()->json($response, 200);
        }

        $subscription = new Subscription([
            'user_id' => $user_id,
            'subID' => $subid,
        ]);
        $subscription->save();

        $response = [
            'message' => 'You subscribed to the user'
        ];
        return response()->json($response, 200);
    }
    public function getsubscribers(Request $request)
    {
        $user = $request->user();
        $subscribers = $user->subscribers()->get();
        return response()->json($subscribers);
    }

}
