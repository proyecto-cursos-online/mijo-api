<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CourseSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'course_id', 'state'];

    protected $dates = ['deleted_at'];

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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function clases()
    {
        return $this->hasMany(CourseClase::class, "course_section_id");
    }
}
