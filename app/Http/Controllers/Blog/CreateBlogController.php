<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\CreateBlogRequest;
use App\Models\Blog;
use Illuminate\Support\Str;
use Exception;

class CreateBlogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateBlogRequest $request)
    {
        try {
            $blog = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'slug' => Str::slug($request->title, '-'),
                'user_id' => 1
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $blog
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'data' => $blog
            ]);
        }
    }
}
