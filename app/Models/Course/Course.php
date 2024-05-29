<?php

namespace App\Models\Course;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sale\Review;
use App\Models\CoursesStudent;
use App\Models\Discount\DiscountCourse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'imagen',
        'precio_usd',
        'precio_pen',
        'categorie_id',
        'sub_categorie_id',
        'user_id',
        'level',
        'idioma',
        'vimeo_id',
        'time',
        'description',
        'requirements',
        'who_is_it_for',
        'state',
    ];

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::now('America/Lima');
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = Carbon::now('America/Lima');
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
        return $this->belongsTo(Category::class, 'sub_categorie_id');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function discount_courses()
    {
        return $this->hasMany(DiscountCourse::class);
    }

    public function courses_students()
    {
        return $this->hasMany(CoursesStudent::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountCAttribute()
    {
        date_default_timezone_set('America/Lima');
        $this->loadMissing('discount_courses.discount'); // Cargar relación si no está ya cargada

        $discount = null;
        foreach ($this->discount_courses as $discount_course) {
            if ($discount_course->discount && $discount_course->discount->type_campaing == 1 && $discount_course->discount->state == 1) {
                if (Carbon::now()->between($discount_course->discount->start_date, Carbon::parse($discount_course->discount->end_date)->addDays(1))) {
                    $discount = $discount_course->discount;
                    break;
                }
            }
        }
        return $discount;
    }

    public function getDiscountCTAttribute()
    {
        date_default_timezone_set('America/Lima');
        $this->loadMissing('category.discount_categories.discount'); // Cargar relación si no está ya cargada

        $discount = null;
        foreach ($this->category->discount_categories as $discount_categorie) {
            if ($discount_categorie->discount && $discount_categorie->discount->type_campaing == 1 && $discount_categorie->discount->state == 1) {
                if (Carbon::now()->between($discount_categorie->discount->start_date, Carbon::parse($discount_categorie->discount->end_date)->addDays(1))) {
                    $discount = $discount_categorie->discount;
                    break;
                }
            }
        }
        return $discount;
    }

    public function getFilesCountAttribute()
    {
        $files_count = 0;
        foreach ($this->sections as $section) {
            foreach ($section->clases as $clase) {
                $files_count += $clase->files->count();
            }
        }
        return $files_count;
    }

    public function getCountClassAttribute()
    {
        $num = 0;
        foreach ($this->sections as $section) {
            $num += $section->clases->count();
        }
        return $num;
    }

    public function getTimeCourseAttribute()
    {
        $times = [];
        foreach ($this->sections as $section) {
            foreach ($section->clases as $clase) {
                $times[] = $clase->time;
            }
        }
        return $this->AddTimes($times);
    }

    public function getCountStudentsAttribute()
    {
        return $this->courses_students->count();
    }

    public function getCountReviewsAttribute()
    {
        return $this->reviews->count();
    }

    public function getAvgReviewsAttribute()
    {
        return $this->reviews->avg('rating');
    }

    private function AddTimes($horas)
    {
        $total = 0;
        foreach ($horas as $h) {
            $parts = explode(':', $h);
            $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
        }
        $hours = floor($total / 3600);
        $minutes = floor(($total / 60) % 60);
        $seconds = $total % 60;
        return "$hours hrs $minutes mins";
    }

    public function scopeFilterAdvance($query, $search, $state)
    {
        if ($search) {
            $query->where('title', 'like', "%$search%");
        }
        if ($state) {
            $query->where('state', $state);
        }
        return $query;
    }

    public function scopeFilterAdvanceEcommerce($query, $search, $selected_categories = [], $instructores_selected = [], $min_price = 0, $max_price = 0, $idiomas_selected = [], $levels_selected = [], $courses_a = [], $rating_selected = 0)
    {
        if ($search) {
            $query->where('title', 'like', "%$search%");
        }
        if (count($selected_categories) > 0) {
            $query->whereIn('categorie_id', $selected_categories);
        }
        if (count($instructores_selected) > 0) {
            $query->whereIn('user_id', $instructores_selected);
        }
        if ($min_price > 0 && $max_price > 0) {
            $query->whereBetween('precio_usd', [$min_price, $max_price]);
        }
        if (count($idiomas_selected) > 0) {
            $query->whereIn('idioma', $idiomas_selected);
        }
        if (count($levels_selected) > 0) {
            $query->whereIn('level', $levels_selected);
        }
        if ($courses_a || $rating_selected) {
            $query->whereIn('id', $courses_a);
        }
        return $query;
    }
}