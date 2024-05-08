<?php

namespace App\Http\Resources\Course\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id'  => $this->resource->id,
      'instructor_id' => $this->resource->instructor_id,
      'instructor' => [
        "id" => $this->resource->instructor->user->id,
        "full_name" => $this->resource->instructor->user->name . " " . $this->resource->instructor->user->surname,
        "email" => $this->resource->instructor->user->email
      ],
      'category_id' => $this->resource->category_id,
      'category' => [
        "id" => $this->resource->category->id,
        "name" => $this->resource->category->name
      ],
      'sub_category_id' => $this->resource->sub_category_id,
      'sub_category' => [
        "id" => $this->resource->sub_category->id,
        "name" => $this->resource->sub_category->name,
      ],
      'vimeo_id' => $this->resource->vimeo_id,
      'title' => $this->resource->title,
      'slug' => $this->resource->slug,
      'subtitle' => $this->resource->subtitle,
      'level' => $this->resource->level,
      'language' => $this->resource->language,
      'time' => $this->resource->time,
      'description' => $this->resource->description,
      'requirements' => json_decode($this->resource->requirements),
      'participant' => json_decode($this->resource->participant),
      'price_in_dollar' => $this->resource->price_in_dollar,
      'price_in_soles' => $this->resource->price_in_soles,
      'state' => $this->resource->state,
      'backgroud_image' => env("APP_URL") . "/storage/" . $this->resource->backgroud_image,
      "deleted_at" => $this->resource->deleted_at ? $this->resource->deleted_at->format("Y-m-d h:i:s") : NULL,
    ];
  }
}
