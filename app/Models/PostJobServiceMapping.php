<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostJobServiceMapping extends Model
{
    use HasFactory;
    protected $table = 'post_job_service_mappings';
    protected $fillable = [
         'post_request_id', 'service_id'
    ];
    protected $casts = [
        'post_request_id'  => 'integer',
        'service_id'  => 'integer',
    ];
}
