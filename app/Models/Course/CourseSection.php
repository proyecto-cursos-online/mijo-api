<?php

namespace App\Models\Course;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSection extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'course_sections';

  protected $fillable = [
    'name',
    'course_id',
    'state'
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

  public function course() {
    return $this->belongsTo(Course::class, 'course_id');
  }

  public function classes() {
    return $this->hasMany(CourseClass::class, 'section_id');
  }
}
