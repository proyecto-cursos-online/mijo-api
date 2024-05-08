<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\Category\CategoryCollection;
use App\Http\Resources\Course\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $state = $request->state;

    $categories = Category::filterAdvance($search, $state)->orderBy("id", "desc")->get();
    return response()->json([
      "categories" => CategoryCollection::make($categories)
    ]);
  }

  public function store(Request $request)
  {
    if ($request->hasFile("image")) {
      $path = Storage::putFile("categories", $request->file("image"));
      $request->request->add(["photo" => $path]);
    }
    $category = Category::create($request->all());
    return response()->json(["category" => CategoryResource::make($category)]);
  }

  public function update(Request $request, string $id)
  {
    $category = Category::findOrFail($id);

    if ($request->hasFile("image")) {
      if ($category->photo) {
        Storage::delete($category->photo);
      }
      $path = Storage::putFile("categories", $request->file("image"));
      $request->merge(["photo" => $path]);
    }

    $category->update($request->all());

    return response()->json(["category" => CategoryResource::make($category)]);
  }

  public function destroy(string $id)
  {
    $category = Category::findOrFail($id);
    $category->delete();
    return response()->json(["message" => 200]);
  }
}
