<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;

/**
 * @OA\Schema(
 *   schema="Post",
 *   title="Post API",
 *   type="object",
 *   @OA\Property(
 *     property="uuid",
 *     type="string",
 *     format="uuid",
 *     description="The unique identifier of the blog post"
 *   ),
 *   @OA\Property(
 *     property="title",
 *     type="string",
 *     description="The title of the blog post"
 *   ),
 *   @OA\Property(
 *     property="content",
 *     type="string",
 *     description="The content of the blog post"
 *   ),
 *   @OA\Property(
 *     property="created_at",
 *     type="string",
 *     format="date-time",
 *     description="The date and time when the blog post was created"
 *   ),
 *   @OA\Property(
 *     property="updated_at",
 *     type="string",
 *     format="date-time",
 *     description="The date and time when the blog post was last updated"
 *   )
 * )
 */
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
     *                 @OA\Items(ref="#/components/schemas/Post")
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
    public function index(): \Illuminate\Http\JsonResponse
    {
        $blogs = Post::paginate()->toArray();
        return response()->json($blogs, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/main/blog/{uuid}",
     *     summary="Get a blog post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="The UUID of the blog post",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The blog post",
     *         @OA\JsonContent(ref="#/components/schemas/BlogPost")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function get(Post $post): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => true, 'data' => $post], 200);
    }
}
