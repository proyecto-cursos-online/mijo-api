<?php

namespace App\Http\Resources\Ecommerce\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseHomeResource extends JsonResource
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
            "slug" => $this->resource->slug,
            "subtitle" => $this->resource->subtitle,
            "imagen" => env("APP_URL") . "storage/" . $this->resource->imagen,
            "precio_usd" => $this->resource->precio_usd,
            "precio_pen" => $this->resource->precio_pen,
            "count_class" => $this->resource->count_class,
            "time_course" => $this->resource->time_course,
            "discount_g" => $discount_g,
            "instructor" => $this->resource->instructor ? [
                "id" => $this->resource->instructor->id,
                "full_name" => $this->resource->instructor->name . ' ' . $this->resource->instructor->surname,
                "profesion" => $this->resource->instructor->profesion,
                "avatar" => env("APP_URL") . "storage/" . $this->resource->instructor->avatar,
            ] : NULL,
        ];
    }
}
