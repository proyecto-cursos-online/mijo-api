<?php

namespace App\Models\Course;

use App\Models\Discount\DiscountCourse;
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

    public function discount_courses()
    {
        return $this->hasMany(DiscountCourse::class);
    }

    public function getDiscountCAttribute()
    {
        date_default_timezone_set("America/Lima");
        $discount = null;
        foreach ($this->discount_courses as $key => $discourse) {
            if ($discourse->discount->type_campaing == 1 && $discourse->discount->state == 1) {
                if (Carbon::now()->between($discourse->discount->start_date, Carbon::parse($discourse->discount->end_date)->addDays(1))) {
                    $discount = $discourse->discount;
                    break;
                }
            }
        };
        return $discount;
    }

    public function getDiscountCTAttribute()
    {
        date_default_timezone_set("America/Lima");
        $discount = null;
        foreach ($this->category->discount_categories as $key => $discategory) {
            if ($discategory->discount->type_campaing == 1 && $discategory->discount->state == 1) {
                if (Carbon::now()->between($discategory->discount->start_date, Carbon::parse($discategory->discount->end_date)->addDays(1))) {
                    $discount = $discategory->discount;
                    break;
                }
            }
        };
        return $discount;
    }

    public function getFilesCountAttribute()
    {
        $files_count = 0;

        foreach ($this->sections as $keyS => $section) {
            foreach ($section->clases as $keyC => $clase) {
                $files_count += $clase->files->count();
            }
        }

        return $files_count;
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