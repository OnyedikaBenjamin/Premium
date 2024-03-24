<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderTaxMapping extends Model
{
    use HasFactory;
    protected $table = 'provider_taxes';
    protected $fillable = [
        'provider_id', 'tax_id'
    ];
    protected $casts = [
        'provider_id'    => 'integer',
        'tax_id' => 'integer',
    ];        
    public function taxes(){
        return $this->belongsTo(Tax::class, 'tax_id','id');
    }
}
