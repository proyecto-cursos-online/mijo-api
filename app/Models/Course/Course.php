<?php

namespace App\Models\Course;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'subtitle', 'slug', 'imagen', 'precio_usd','precio_pen', 'category_id', 'sub_category_id', 'user_id', 'level', 'idioma', 'vimeo_id', 'time', 'description', 'requirements', 'who_is_it_for', 'state',];

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
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function sub_category()
    {
        return $this->belongsTo(Category::class);
    }
    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }
}
