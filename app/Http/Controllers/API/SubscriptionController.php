<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{

    /**
     * Display a listing of all subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Subscription::latest()->get();
        return response()->json([SubscriptionResource::collection($data), 'Subscriptions fetched.']);
    }

    /**
     * Store a newly created post and send mail to all subscribers of the website.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'url' => 'required|string|max:255|exists:websites,url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //Get User's detail
        $User = new User();
        $user = $User->getUserId($request->email);
        //Get Website detail
        $Website = new Website();
        $website = $Website::where('url', '=', $request->url)->first();

        //create the subscription
        $subscribed = Subscription::firstOrCreate([ //ensure that users can only subscribe to a particular website once in the database table
            'website_id' => $website->id,
            'user_id' => $user->id,
        ]);

        if ($subscribed) {
            return response()->json(['Subscription created successfully.', new SubscriptionResource($subscribed)]);
        } else {
            return response()->json(['Subscription creation unsuccessful, kindly try again']);
        }

    }
}
