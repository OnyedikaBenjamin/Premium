<?php

namespace App\Models;

use App\Models\Traits\HasHashedMediaTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BaseModel extends Model
{
    use SoftDeletes;
    /* get status value */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case '0':
                return '<span class="badge bg-soft-danger">Inactive</span>';
                break;

            case '1':
                return '<span class="badge bg-soft-success">Active</span>';
                break;

            case '2':
                return '<span class="badge bg-soft-warning text-dark">Pending</span>';
                break;

            default:
                return '<span class="badge bg-soft-primary">Status:'.$this->status.'</span>';
                break;
        }
    }

    public function getStatus()
    {

        switch ($this->status) {
            case '1':
                return '<span class="badge bg-success">Active</span>';
                break;
            case '2':
                return '<span class="badge bg-warning text-dark">Inactive</span>';
                break;
            default:
                return '<span class="badge bg-primary">Status:'.$this->status.'</span>';
                break;
        }
    }

    /**
     * Get Status Label.
     *
     * @return [type] [description]
     */
    public function getStatusLabelTextAttribute()
    {
        switch ($this->status) {
            case '0':
                return 'Inactive';
                break;

            case '1':
                return 'Active';
                break;

            case '2':
                return 'Pending';
                break;

            default:
                return $this->status;
                break;
        }
    }

    
}
