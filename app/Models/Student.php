<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'department_id',
        'student_number',
        'full_name',
        'email'
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
