<?php

namespace App\Http\Controllers\Blog;

use App\Events\BlogCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Exception;
use Illuminate\Http\JsonResponse;

class IndexBlogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        BlogCreatedEvent::dispatch('asdf');
        try {
            $blog = Blog::all();
            return response()->json([
                'status' => 'success',
                'data' => $blog,
            ], 200);
        } catch (Exception $e) {
            report($e);
        }
    }
}
