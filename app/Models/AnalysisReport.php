<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalysisReport extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'semester_id',
        'report_type',
        'title',
        'input_snapshot',
        'metrics_json',
        'ai_summary',
        'recommendations'
    ];

    protected $casts = [
        'input_snapshot'=>'array',
        'metrics_json'=>'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
