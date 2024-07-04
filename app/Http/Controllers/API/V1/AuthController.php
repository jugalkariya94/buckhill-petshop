<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\JWTService;
use App\Services\UserService;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    private JWTService $jwtService;
    private AuthService $authService;
    private UserService $userService;

    public function __construct(JWTService $jwtService, AuthService $authService, UserService $userService)
    {
        $this->jwtService = $jwtService;
        $this->authService = $authService;
        $this->userService = $userService;
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
     *              @OA\Property(property="avatar", type="string", example="48da3352-8d93-41a4-ab67-e17674dbc307", description="The avatar of the user", required=false),
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=400, description="Bad request")
     *     @OA\Response(response=422, description="Validation error")
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function createUser(CreateUserRequest $request): JsonResponse
    {
        try {

            // Register the user
            $userData = $request->safe()->only(['first_name', 'last_name', 'email', 'password', 'address', 'phone_number', 'is_marketing', 'avatar']);
            $user = $this->authService->register($userData);

            // Create a token for the user
            // remove comment to enable JWT token on registration
            // $token = $this->jwtService->createToken($user);

            return response()->json(['success' => true, 'data' => $user], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="johndoe@example.com", description="The email of the user", required=true),
     *             @OA\Property(property="password", type="string", example="password", description="The password of the user", required=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Unauthorized")
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->login($request->email, $request->password);
            $token = $this->jwtService->createToken($user);
            return response()->json(['success' => true, 'access_token' => $token, 'data' => $user]);
        } catch (InvalidCredentialsException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Get authenticated user data",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function get(Request $request): JsonResponse
    {
        try {
            // get user details
            $user = auth()->user();
            return response()->json(['success' => true, 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/forgot-password",
     *     summary="Forgot password",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Email sent if user exists"),
     *     @OA\Response(response=422, description="Validation error")
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $email = $request->email;
            $this->authService->sendPasswordResetLink($email);

            // Following code is to get reset token for the user
            // this is only for the current test purposes and shouldn't be used while working on actual application
            $resetToken = DB::table('password_reset_tokens')->where('email', $email)->first();

            return response()->json(['success' => true, 'message' => 'If your email exists in our system, you will receive a password reset link shortly.', 'token' => $resetToken]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/reset-password-token",
     *     summary="Reset password using token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password reset successful"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=400, description="Invalid token")
     *     @OA\Response(response=500, description="Server token")
     * )
     */
    public function resetPasswordToken(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->resetPassword($request->validated());
            return response()->json(['success' => true, 'message' => 'Your password has been reset successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     summary="Delete user",
     *     @OA\Response(response=200, description="User deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     *     @OA\Response(response=500, description="Server error")
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function deleteUser(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            if (!$user || empty($request->bearerToken())) {
                return response()->json(['success' => false, 'error' => 'User not found'], 404);
            }
            $this->userService->delete($user->uuid);
            $this->jwtService->markTokenAsExpired($request->bearerToken());
            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/logout",
     *     summary="User logout",
     *     @OA\Response(response=200, description="Logout successful"),
     *     @OA\Response(response=401, description="Unauthorized")
     *     @OA\Response(response=500, description="Server error")
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $token = request()->bearerToken();
            if (empty($token)) {
                return response()->json(['success' => false, 'error' => 'User not found'], 404);
            }

            // logout operation
            $this->jwtService->markTokenAsExpired($token);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update user information.
     *
     * @OA\Put(
     *     path="/api/v1/user/edit",
     *     summary="Update user information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string", example="John", description="The first name of the user"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="The last name of the user"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com", description="The email of the user"),
     *             @OA\Property(property="password", example="password", type="string"),
     *             @OA\Property(property="password_confirmation", example="password", type="string")
     *             @OA\Property(property="address", type="string", example="1234 Elm St", description="The address of the user"),
     *             @OA\Property(property="phone_number", type="string", example="1234567890", description="The phone number of the user"),
     *             @OA\Property(property="is_marketing", type="boolean", example=true, description="Whether the user wants to receive marketing emails"),
     *             @OA\Property(property="avatar", type="string", example="48da3352-8d93-41a4-ab67-e17674dbc307", description="The avatar of the user")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'User not found'], 404);
            }
            $validatedData = $request->validated();
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }
            $user->update($validatedData);
            // Return success response
            return response()->json(['success' => true, 'message' => 'User updated successfully', 'user' => $user], Response::HTTP_OK);
        } catch (Exception $e) {
            // Return error response if something goes wrong
            return response()->json(['success' => false, 'error' => 'Failed to update user: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
