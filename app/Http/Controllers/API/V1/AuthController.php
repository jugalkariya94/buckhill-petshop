<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\JWTService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    //
    private JWTService $jwtService;
    private AuthService $authService;

    public function __construct(JWTService $jwtService, AuthService $authService)
    {
        $this->jwtService = $jwtService;
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/create",
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="first_name", type="string", example="John", description="The first name of the user", required=true),
     *              @OA\Property(property="last_name ", type="string", example="Doe", description="The last name of the user", required=true),
     *              @OA\Property(property="email", type="string", example="johndoe@example.com", description="The email of the user", required=true),
     *              @OA\Property(property="password", type="string", example="password", description="The password of the user", required=true),
     *              @OA\Property(property="password_confirmation", type="string", example="password", description="The password confirmation of the user", required=true),
     *              @OA\Property(property="address", type="string", example="1234 Elm St", description="The address of the user", required=true),
     *              @OA\Property(property="phone_number", type="string", example="1234567890", description="The phone number of the user", required=true),
     *              @OA\Property(property="is_marketing", type="boolean", example=true, description="Whether the user wants to receive marketing emails", required=false),
     *              @OA\Property(property="is_admin", type="boolean", example=false, description="Whether the user is an admin", required=false)
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function createUser(Request $request)
    {
        try {
            // Register the user
            $user = $this->authService->register($request->all());

            // Create a token for the user
            // remove comment to enable JWT token on registration
            // $token = $this->jwtService->createToken($user);

            return response()->json(['success' => true], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
