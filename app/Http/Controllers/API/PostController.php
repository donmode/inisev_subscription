<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Jobs\SendEmailJob;
use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    /**
     * Display a listing of all posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Post::latest()->get();
        return response()->json([PostResource::collection($data), 'Posts fetched.']);
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'body' => 'required|string',
            'url' => 'required|exists:websites,url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $website = Website::where('url', '=', $request->url)->first();

        //create the post
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'body' => $request->body,
            'website_id' => $website->id,
        ]);

        //Send the mail to all subscribers via queue
        SendEmailJob::dispatch($post, $website);

        return response()->json(['Post created successfully.', new PostResource($post)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json('Data not found', 404);
        }
        return response()->json([new PostResource($post)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $post->name = $request->name;
        $post->desc = $request->desc;
        $post->save();

        return response()->json(['Post updated successfully.', new PostResource($post)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json('Post deleted successfully');
    }
}
