<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User;

class AuthController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'login_tienda', 'register', 'userProfile']]);
  }

  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $credentials = $validator->validated();
    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      return response()->json(['error' => 'No autorizado'], 401);
    }

    if ($user->type_user != 2 || $user->state != 1) {
      return response()->json(['error' => 'No autorizado'], 401);
    }

    return $this->createNewToken(auth()->login($user));
  }

  public function login_tienda(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $credentials = $validator->validated();
    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      return response()->json(['error' => 'No autorizado'], 401);
    }

    if ($user->state != 1) {
      return response()->json(['error' => 'No autorizado'], 401);
    }

    return $this->createNewToken(auth()->login($user));
  }

  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|between:2,100',
      'surname' => 'required|string|between:2,100',
      'email' => 'required|string|email|max:100|unique:users',
      'password' => 'required|string|min:6',
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }
    $user = User::create(
      array_merge(
        $validator->validated(),
        ['password' => bcrypt($request->password)]
      )
    );
    return response()->json([
      'message' => 'User successfully registered',
      'user' => $user
    ], 201);
  }

  public function logout()
  {
    auth()->logout();
    return response()->json(['message' => 'User successfully signed out']);
  }

  public function refresh()
  {
    return $this->createNewToken(auth()->refresh());
  }

  public function user_profile()
  {
    $user = auth()->user();

    if (!$user) {
      return response()->json(['error' => 'No autorizado'], 401);
    }

    return response()->json($user);
  }

  protected function createNewToken($token)
  {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60,
      'user' => auth()->user()
    ]);
  }
}