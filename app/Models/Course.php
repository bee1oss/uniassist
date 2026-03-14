<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'department_id',
        'code',
        'name',
        'credits'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }
}
