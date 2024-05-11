<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseGCollection;
use App\Http\Resources\Course\CourseGResource;
use App\Models\Course\Category;
use App\Models\Course\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseGController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $state = $request->state;
//filterAdvance($search, $state)->
        $courses = Course::orderBy("id", "desc")->get();
        return response()->json([
            "courses" => CourseGCollection::make($courses),
        ]);
    }

    public function config(){
        $categories= Category::where("category_id", NULL)->orderBy("id","desc")->get();
        $subcat = Category::where("category_id", "<>", NULL)->orderBy("id","desc")->get();

        $instru = User::where("is_instructor", 1)->orderBy("id","desc")->get();

        return response()->json([
            "categories" => $categories,
            "subcategories" => $subcat,
            "instructores" => $instru->map(function($user){
                return [
                    "id" => $user ->id,
                    "full_name"=> $user ->name.' '. $user ->surname,
                ];
            }),
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
        if ($request->hasFile("portada")) {
            $path = Storage::putFile("courses", $request->file("portada"));
            $request->request->add(["imagen" => $path]);
        }
        $request -> request->add(["slug"=>Str::slug($request->title)]);
        $request -> request->add(["requirements"=>json_encode($request->requirements)]);
        $request -> request->add(["who_is_it_for"=>json_encode($request->who_is_it_for)]);
    
        $course = Course::create($request->all());
        return response()->json([
            "course" => CourseGResource::make($course)
        ]);
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
        $course = course::findOrFail($id);
        if ($request->hasFile("portada")) {
            if ($course->imagen) {
                Storage::delete($course->imagen);
            }
            $path = Storage::putFile("courses", $request->file("portada"));
            $request->request->add(["imagen" => $path]);
        }
        $request -> request->add(["slug"=>Str::slug($request->title)]);
        $request -> request->add(["requirements"=>json_encode($request->requirements)]);
        $request -> request->add(["who_is_it_for"=>json_encode($request->who_is_it_for)]);
    
        $course->update($request->all());

        return response()->json([" course" => CourseGResource::make($course)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(["message" => 200]);
    }
}
