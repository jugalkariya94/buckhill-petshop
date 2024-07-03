<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/main/blog",
     *     summary="List all blog posts",
     *     tags={"Posts"},
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of blog posts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BlogPost")
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/v1/main/blog?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://example.com/api/v1/main/blog?page=1"),
     *                 @OA\Property(property="prev", type="null", example=null),
     *                 @OA\Property(property="next", type="null", example=null)
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/v1/main/blog"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=10)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        $blogs = Post::paginate()->toArray();
        return response()->json($blogs, 200);
    }
}
