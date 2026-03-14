<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = [
        'name',
        'year',
        'term'
    ];

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    public function analysisReports()
    {
        return $this->hasMany(AnalysisReport::class);
    }
}
