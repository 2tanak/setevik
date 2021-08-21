<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }
}
