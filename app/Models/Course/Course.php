<?php

namespace App\Models\Course;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "title",
        "subtitle",
        "slug",
        "imagen",
        "precio_usd",
        "precio_pen",
        "category_id",
        "sub_categorie_id",
        "user_id",
        "level",
        "idioma",
        "vimeo_id",
        "time",
        "description",
        "requirements",
        "what_is_it_for",
        "state"
    ];

    //protected $dates = ['deleted_at'];

    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["created_at"] = Carbon::now();
    }

    public function setUpdateAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"] = Carbon::now();
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function sub_categorie()
    {
        return $this->belongsTo(Category::class);
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }
    function AddTimes($horas)
    {
        $total = 0;
        foreach ($horas as $h) {
            $parts = explode(":", $h);
            $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
        }
        $hours = floor($total / 3600);
        $minutes = floor(($total / 60) % 60);
        $seconds = $total % 60;

        return $hours . " hrs " . $minutes . " mins";
    }

    public function getCountClassAttribute()
    {
        $num = 0;
        foreach ($this->sections as $key => $section) {
            $num += $section->clases->count();
        }
        return $num;
    }

    public function getTimeCourseAttribute()
    {
        $times = [];
        foreach ($this->sections as $keyS => $section) {
            foreach ($section->clases as $keyc => $clase) {
                array_push($times, $clase->time);
            }
        }
        return $this->AddTimes($times);
    }

    function scopeFilterAdvance($query, $search, $state)
    {
        if ($search) {
            $query->where("title", "like", "%" . $search . "%");
        }
        if ($state) {
            $query->where("state", $state);
        }
        return $query;
    }
}
