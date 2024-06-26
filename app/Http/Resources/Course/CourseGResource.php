<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseGResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "title" => $this->resource->title, 
            "subtitle" => $this->resource->subtitle,
            "slug" => $this->resource->slug, 
            "imagen" => env("APP_URL")."storage/".$this->resource->imagen, 
            "category_id" => $this->resource->category_id, 
            "category" => [
                "id" => $this->resource->category->id,
                "name" => $this->resource->category->name
            ], 
            "sub_category_id" => $this->resource->sub_category_id, 
            "sub_category" => [
                "id" => $this->resource->sub_category->id,
                "name" => $this->resource->sub_category->name
            ], 
            "precio_usd" => $this->resource->precio_usd,
            "precio_pen" => $this->resource->precio_pen,
            "user_id" => $this->resource->user_id,
            "user" => [
                "id" => $this->resource->user->id,
                "full_name" => $this->resource->user->name.' '.$this->resource->user->surname,
                "email" => $this->resource->user->email,
            ], 
            "level" => $this->resource->level, 
            "idioma" => $this->resource->idioma, 
            "vimeo_id" => $this->resource->vimeo_id,// 
            "time" => $this->resource->time, 
            "description" => $this->resource->description, 
            "requirements" => json_decode($this->resource->requirements), 
            "who_is_it_for" => json_decode($this->resource->who_is_it_for), 
            "state" => $this->resource->state,
        ];
    }
}
