<?php

namespace App\Models;

use App\Traits\Ownable;
use App\Traits\Fileable;

use Illuminate\Database\Eloquent\Model;

class Quittance extends Model
{
    use Ownable, Fileable;

    protected $fillable = [
        'user_id',
        'file_id',
    ];

    /**
     * Default directory in storage
     *
     * @var string
     */
    protected $defaultDir = '/storage/quittances';

}
