<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\Course\CourseCollection;
use App\Http\Resources\Course\Course\CourseResource;
use App\Models\Category;
use App\Models\Course\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $state = $request->state;

    /* $courses = Course::filterAdvance($search, $state)->orderBy("id", "desc")->get(); */
    $courses = Course::orderBy("id", "desc")->get();
    return response()->json([
      "courses" => CourseCollection::make($courses)
    ]);
  }

  public function config() {
    $categories = Category::where("category_id", NULL)->orderBy("id", "desc")->get();
    $subcategories = Category::where("category_id", "<>", NULL)->orderBy("id", "desc")->get();
    $instructors = Instructor::all();
    return response()->json([
      "categories" => $categories,
      "subcategories" => $subcategories,
      "instructors" => $instructors->map(function($user) {
        return [
          "id" => $user->id,
          "full_name" => $user->user->name . " " . $user->user->surname
        ];
      })
    ]);
  }

  public function store(Request $request)
  {
    $is_exists = Course::where("title", $request->title)->first();
    if ($is_exists) {
      return response()->json(["message" => 403, "message_text" => "Ya existe un curso con este titulo"]);
    }
    if ($request->hasFile("image")) {
      $path = Storage::putFile("courses", $request->file("image"));
      $request->request->add(["backgroud_image" => $path]);
    }
    $request->request->add(["slug" => Str::slug($request->title)]);
    $request->request->add(["requirements" => json_encode(explode(",", $request->requirements))]);
    $request->request->add(["participant" => json_encode(explode(",", $request->participants))]);
    $course = Course::create($request->all());
    return response()->json(["course" => CourseResource::make($course)]);
  }

  public function update(Request $request, string $id)
  {
    $is_exists = Course::where("id", "<>", $id)->where("title", $request->title)->first();
    if ($is_exists) {
      return response()->json(["message" => 403, "message_text" => "Ya existe un curso con este titulo"]);
    }
    $course = Course::findOrFail($id);

    if ($request->hasFile("image")) {
      if ($course->photo) {
        Storage::delete($course->photo);
      }
      $path = Storage::putFile("courses", $request->file("image"));
      $request->merge(["backgroud_image" => $path]);
    }
    $request->request->add(["slug" => Str::slug($request->title)]);
    $request->request->add(["requirements" => json_encode(explode(",", $request->requirements))]);
    $request->request->add(["participant" => json_encode(explode(",", $request->participants))]);
    $course->update($request->all());

    return response()->json(["course" => CourseResource::make($course)]);
  }

  public function show($id) {
    $course = Course::findOrFail($id);
    return response()->json(["course" => CourseResource::make($course)]);
  }

  public function destroy(string $id)
  {
    $course = Course::findOrFail($id);
    $course->delete();
    return response()->json(["message" => 200]);
  }
}
