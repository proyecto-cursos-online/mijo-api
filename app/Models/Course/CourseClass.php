<?php

namespace App\Models\Course;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'course_classes';

    protected $fillable = [
      'section_id',
      'vimeo_id',
      'name',
      'time',
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

  public function section() {
    return $this->belongsTo(CourseSection::class, 'section_id');
  }

  public function files() {
    return $this->hasMany(CourseFiles::class, 'class_id');
  }
}
