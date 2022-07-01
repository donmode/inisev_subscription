<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteResource;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{

    /**
     * Display a listing of all websites.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Website::latest()->get();
        return response()->json([WebsiteResource::collection($data), 'Websites fetched.']);
    }

    /**
     * Store a newly created website.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'url' => 'required|string|max:255|unique:websites,url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $post = Website::create([
            'name' => $request->name,
            'url' => $request->url,
        ]);

        return response()->json(['Website created successfully.', new WebsiteResource($post)]);
    }
}
