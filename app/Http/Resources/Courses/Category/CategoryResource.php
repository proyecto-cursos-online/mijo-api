<?php

namespace App\Http\Resources\Courses\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      "id" => $this->resource->id,
      "name" => $this->resource->name,
      "state" => $this->resource->state ?? 1,
      "photo" => $this->resource->photo ? env("APP_URL") . "/storage/" . $this->resource->photo :  NULL,
      "category_id" => $this->resource->category_id,
      "category" => $this->resource->father ? [
        "name" => $this->resource->father->name,
        "photo" => env("APP_URL") . "/storage/" . $this->resource->father->photo,
      ]: NULL,
      "created_at" => $this->resource->created_at ? $this->resource->created_at->format("Y-m-d h:i:s"): NULL,
      "updated_at" => $this->resource->updated_at ? $this->resource->updated_at->format("Y-m-d h:i:s"): NULL,
      "deleted_at" => $this->resource->deleted_at ? $this->resource->deleted_at->format("Y-m-d h:i:s") : NULL,
    ];
  }
}
