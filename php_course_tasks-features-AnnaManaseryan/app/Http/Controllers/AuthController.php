<?php
namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','registration']]);
    }

    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'],401);
        }

        return $this->respondWithToken($token);
    }

    public function registration(RegistrationRequest $request): JsonResponse
    {
        $request = request(['first_name','last_name','email','gender','password']);
        $request['password'] = bcrypt($request['password']);
        $user = new User();
        $user -> fill($request);
        $user ->save();

        return response()->json([
            'status' => 1,
            'message' => 'The user was added successfully.',
        ]);
    }


//    public function registration(RegistrationRequest $request): JsonResponse
//    {
//        try {
//            $authData = $request->only(['first_name', 'email', 'password']);
//
//            $user = User::create(array_merge($authData, ['password' => bcrypt($request->password)]));
//
//            $token = auth()->attempt($authData);
//
//            return response()->json([
//                'message' => 'User successfully registered',
//                'token' => $token,
//                'user' => $user,
//            ], 201);
//        } catch (\Exception $e) {
//            return response()->json([
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }

    public function me()
    {
        return response()->json(auth()->user());
    }


    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'You are successfully logged out.']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken(string $token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
