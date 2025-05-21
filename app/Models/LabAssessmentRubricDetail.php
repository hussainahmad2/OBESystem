<?php

namespace App\Models;
use \App\Models\Faculty;
use App\Models\Role;
use \App\Models\Stu;
use \App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class LabAssessmentRubricDetail extends Model
{
    
    protected $table = 'lab_assessment_rubric_details';
    // protected $fillable = [
    //     'assessment_id', 
    //     'clo_number', 
    //     'total_marks', 
    //     'obtained_marks',
    //     'rubric_number',
    //     'lab_assessment_id'
    // ];

    protected $fillable = ['lab_assessment_id', 'rubric_number', 'total_marks', 'obtained_marks'];


    public function assessment() {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }
}
