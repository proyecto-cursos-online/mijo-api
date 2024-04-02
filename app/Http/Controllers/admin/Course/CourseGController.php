<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Resources\Course\CourseGCollection;
use App\Http\Resources\Course\CourseGResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CourseGController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $state = $request->state;

        //filterAdvance($search, $state)->
        $courses = Course::orderBy("id", "desc")->get();
        return response()->json([
            "courses" => CourseGCollection::make($courses)
        ]);
    }

    public function store(Request $request)
    {
        if ($request->hasFile("portada")) {
            $path = Storage::putFile("courses", $request->file("portada"));
            $request->request->add(["imagen" => $path]);
        }
        $request->request->add(["slug" => Str::slug($request->title)]);
        $request->request->add(["requirements" => json_encode($request->requirements)]);
        $request->request->add(["who_is_it_for" => json_encode($request->who_is_it_for)]);
        $course = Course::create($request->all());

        return response()->json(["course" => CourseGResource::make($course)]);
    }

    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        if ($request->hasFile("portada")) {
            if ($course->imagen) {
                Storage::delete($course->imagen);
            }
            $path = Storage::putFile("courses", $request->file("portada"));
            $request->merge(["imagen" => $path]);
        }
        $request->request->add(["slug" => Str::slug($request->title)]);
        $request->request->add(["requirements" => json_encode($request->requirements)]);
        $request->request->add(["who_is_it_for" => json_encode($request->who_is_it_for)]);
        $course->update($request->all());

        return response()->json(["course" => CourseGResource::make($course)]);
    }

    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(["message" => 200]);
    }
}
