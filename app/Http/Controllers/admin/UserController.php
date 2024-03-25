<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
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
    return response()->json(["user" => $user]);
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
    if ($request->hasFile("imagen")) {
      if ($user->avatar) {
        Storage::delete($user->avatar);
      }
      $path = Storage::putFile("users", $request->file("imagen"));
      $request->request->add(["avatar" => $path]);
    }
    if ($request->password) {
      $request->request->add(["password" => bcrypt($request->password)]);
    }
    $user->update($request->all());
    return response()->json(["user" => $user]);
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
