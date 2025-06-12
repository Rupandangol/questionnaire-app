<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Exception;
use Illuminate\Http\JsonResponse;

class ShowBlogController extends Controller
{
    public function __invoke($id, $slug): JsonResponse
    {
        // try {
        $data = Blog::where(['id' => $id, 'slug' => $slug])->first();
        if ($data == null) {
            throw new Exception('Blog not available', 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $e->getMessage()
        //     ]);
        // }
    }
}
