<?php

namespace App\Http\Controllers\Blog;

use App\Events\BlogDeletedEvent;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Exception;
use Illuminate\Support\Facades\DB;

class DeleteBlogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $id, string $slug)
    {
        DB::beginTransaction();
        try {
            $blog = Blog::where(['id' => $id, 'slug' => $slug])->first();
            if ($blog == null) {
                throw new Exception('Not Found ', 404);
            }
            $blog->delete();
            BlogDeletedEvent::dispatch($blog);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted Successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
