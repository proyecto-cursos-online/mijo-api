<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserGCollection;
use App\Http\Resources\User\UserGResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $search = $request->search;
    $state = $request->state;

    $users = User::filterAdvance($search, $state)->where("type_user", 2)->orderBy("id", "desc")->get();
    return response()->json([
      "users" => UserGCollection::make($users)
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    if ($request->hasFile("image")) {
      $path = Storage::putFile("users", $request->file("image"));
      $request->request->add(["avatar" => $path]);
    }
    if ($request->password) {
      $request->request->add(["password" => bcrypt($request->password)]);
    }
    $user = User::create($request->all());
    return response()->json(["user" => UserGResource::make($user)]);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $user = User::findOrFail($id);
    // Verificar si se proporcionó una nueva contraseña
    if ($request->has('password')) {
      // Verificar si la contraseña proporcionada es diferente de la actual
      if ($request->password !== $user->password) {
        // Si es diferente, hashear la nueva contraseña y actualizar el campo
        $request->merge(['password' => bcrypt($request->password)]);
      } else {
        // Si es igual, eliminar el campo de la solicitud para que no se actualice
        $request->request->remove('password');
      }
    }

    // Procesar la actualización del avatar si se proporcionó uno nuevo
    if ($request->hasFile("image")) {
      if ($user->avatar) {
        Storage::delete($user->avatar);
      }
      $path = Storage::putFile("users", $request->file("image"));
      $request->merge(["avatar" => $path]);
    }

    // Actualizar el usuario con los datos proporcionados
    $user->update($request->all());

    return response()->json(["user" => UserGResource::make($user)]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $user = User::findOrFail($id);
    $user->delete();
    return response()->json(["message" => 200]);
  }
}
