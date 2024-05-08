<?php

namespace App\Models\Course;

use App\Models\Category;
use App\Models\Instructor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
      'instructor_id',
      'category_id',
      'sub_category_id',
      'vimeo_id',
      'title',
      'slug',
      'subtitle',
      'level',
      'language',
      'time',
      'description',
      'requirements',
      'participant',
      'price_in_dollar',
      'price_in_soles',
      'state',
      'backgroud_image'
  ];

  public function setCreatedAtAttribute($value)
  {
    date_default_timezone_set("America/Lima");
    $this->attributes["created_at"] = Carbon::now();
  }

  public function setUpdatedAtAttribute($value)
  {
    date_default_timezone_set("America/Lima");
    $this->attributes["updated_at"] = Carbon::now();
  }

  public function setDeletedAtAttribute($value)
  {
    date_default_timezone_set("America/Lima");
    $this->attributes["deleted_at"] = Carbon::now();
  }

  public function instructor() {
    return $this->belongsTo(Instructor::class, 'instructor_id');
  }

  public function category() {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function sub_category() {
    return $this->belongsTo(Category::class, 'sub_category_id');
  }

  public function sections() {
    return $this->hasMany(CourseSection::class, 'course_id');
  }
}
