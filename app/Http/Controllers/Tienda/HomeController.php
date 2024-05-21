<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\Course\CourseHomeCollection;
use App\Models\Course\Category;
use App\Models\Course\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::where("category_id", null)->withCount("courses")->orderBy("id", "desc")->get();
        $courses = Course::where("state", 2)->inRandomOrder()->limit(3)->get();
        $categories_courses = Category::where("category_id", null)->withCount("courses")->having("courses_count", ">", 0)->orderBy("id", "desc")->take(5)->get();
        $group_courses_categories = collect([]);

        foreach ($categories_courses as $key => $categories_course) {
            $group_courses_categories->push(
                [
                    "id" => $categories_course->id,
                    "name" => $categories_course->name,
                    "name_empty" => str_replace(" ","", $categories_course->name),
                    "courses_count" => $categories_course->course_count,
                    "courses" => CourseHomeCollection::make($categories_course->courses),
                ]
            );
        };
        return response()->json([
            "categories" => $categories->map(
                function ($categories) {
                    return [
                        "id" => $categories->id,
                        "name" => $categories->name,
                        "imagen" => env("APP_URL") . "storage/" . $categories->imagen,
                        "course_count" => $categories->courses_count,
                    ];
                }
            ),
            "courses_home" => CourseHomeCollection::make($courses),
            "group_courses_categories" => $group_courses_categories,
        ]);
    }
}
