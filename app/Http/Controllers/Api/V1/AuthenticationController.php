<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Foreign Exchange API",
 *      description="Monedas de uso oficial en Venezuela",
 *      @OA\Contact(
 *          email="test@test.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\SecurityScheme(
 *    securityScheme="bearerAuth",
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT"
 * )
 */
class AuthenticationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/register",
     *      operationId="registerUser",
     *      tags={"Auth"},
     *      summary="Register a new user",
     *      @OA\RequestBody(
     *          required=true,
     *           @OA\JsonContent(
     *              type="object",
     *               required={"name", "email", "password", "password_confirmation"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="test@test.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *           ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *           @OA\JsonContent(
     *              type="object",
     *                @OA\Property(property="user", type="object"),
     *                @OA\Property(property="token", type="string")
     *           )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *       ),
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      operationId="loginUser",
     *      tags={"Auth"},
     *      summary="Login a user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"email", "password"},
     *                  @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *                  @OA\Property(property="password", type="string", format="password", example="password"),
     *               )
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\JsonContent(
     *              type="object",
     *                @OA\Property(property="token", type="string")
     *           )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="invalid credentials",
     *       ),
     *    @OA\Response(
     *          response=500,
     *          description="Server Internal Error",
     *      ),
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            $user = auth()->user();

            return response()->json(compact('token'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/user",
     *      operationId="getUser",
     *      tags={"Auth"},
     *      summary="Get authenticated user information",
     *      security={{"bearerAuth":{}}},
     *       @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\JsonContent(
     *              type="object",
     *                @OA\Property(property="user", type="object")
     *           )
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="User Not Found",
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Invalid Token",
     *      ),
     * )
     */
    public function getUser(): JsonResponse
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user",
     *      operationId="logoutUser",
     *      tags={"Auth"},
     *      summary="Logout authenticated user",
     *       security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\JsonContent(
     *              type="object",
     *                @OA\Property(property="message", type="string")
     *           )
     *       ),
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
