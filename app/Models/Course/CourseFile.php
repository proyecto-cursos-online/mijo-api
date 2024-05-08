<?php

namespace App\Models\Course;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseFiles extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'class_files_by_course';

  protected $fillable = [
    'class_id',
    'file_name',
    'size',
    'time',
    'resolution',
    'file',
    'type',
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
}
