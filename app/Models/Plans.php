<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;
    protected $table = 'plans';
    protected $fillable = [
        'title', 'identifier', 'type', 'amount','status','duration','description','trial_period','plan_type'
    ];
    protected $casts = [
        'amount'    => 'double',
        'status'    => 'integer',
        'trial_period'    => 'integer',
    ];
    public function planlimit(){
        return $this->belongsTo(PlanLimit::class,'id', 'plan_id');
    }
    public function staticdata(){
        return $this->belongsTo(StaticData::class,'plan_type', 'id');
    }
}
