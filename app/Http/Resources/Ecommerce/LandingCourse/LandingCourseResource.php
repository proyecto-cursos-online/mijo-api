<?php

namespace App\Http\Resources\Ecommerce\LandingCourse;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discount_g = null;
        if ($this->resource->discount_c && $this->resource->discount_c_t) {
            $discount_g = $this->resource->discount_c_t;
        } else {
            if ($this->resource->discount_c && !$this->resource->discount_c_t) {
                $discount_g = $this->resource->discount_c;
            } else {
                if (!$this->resource->discount_c && $this->resource->discount_c_t) {
                    $discount_g = $this->resource->discount_c_t;
                }
            }
        }

        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "subtitle" => $this->resource->subtitle,
            "category_id" => $this->resource->category_id,
            "category" => [
                "id" => $this->resource->category->id,
                "name" => $this->resource->category->name,
            ],
            "sub_categorie_id" => [
                "id" => $this->resource->sub_categorie->id,
                "name" => $this->resource->sub_categorie->name,
            ],
            "sub_categorie" => $this->resource->sub_categorie,
            "level" => $this->resource->level,
            "idioma" => $this->resource->idioma,
            "vimeo_id" => $this->resource->vimeo_id ? "https://player.vimeo.com/video/".$this->resource->vimeo_id : NULL,
            "time" => $this->resource->time,
            "imagen" => env("APP_URL")."storage/".$this->resource->imagen,
            "precio_usd" => $this->resource->precio_usd,
            "precio_pen" => $this->resource->precio_pen,
            "count_class" => $this->resource->count_class,
            "time_course" => $this->resource->time_course,
            "files_count" => $this->resource->files_count,
            "discount_g" => $discount_g,
            "discount_date" => $discount_g ? Carbon::parse($discount_g->end_date)->format("d/m") : NULL,
            "description" => $this->resource->description,
            "requirements" => json_decode($this->resource->requirements),
            "what_is_it_for" => json_decode($this->resource->what_is_it_for),
            "instructor" => $this->resource->instructor ? [
                "id" => $this->resource->instructor->id,
                "full_name" => $this->resource->instructor->name. ' '. $this->resource->instructor->surname,
                "avatar" => env("APP_URL")."storage/".$this->resource->instructor->avatar,
                "profesion" => $this->resource->instructor->profesion,
                "courses_count"  => $this->resource->instructor->courses_count,
                "description" => $this->resource->instructor->description,
            ] : NULL,
            // MALLA CURRICULAR
            "malla" => $this->resource->sections->map(function($section){
                return [
                    "id" => $section->id,
                    "name" => $section->name,
                    "time_section" => $section->time_section,
                    "clases" => $section->clases->map(function($clase) {
                        return [
                            "id" => $clase->id,
                            "name" => $clase->name,
                            "time_clase" => $clase->time_clase,
                        ];
                    })
                ];
            }),
            "updated_at" => $this->resource->updated_at->format("m/Y"),
        ];
    }
}
