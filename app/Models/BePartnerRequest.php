<?php

namespace App\Models;

use App\User;
use App\Traits\Ownable;
use App\Traits\Badgable;
use App\Observers\BePartnerRequestObserver;

use Illuminate\Database\Eloquent\Model;

class BePartnerRequest extends Model
{
    use Ownable, Badgable;

    protected $fillable = [
        'user_id',
        'curator_id',
        'quittance_id',
        'package_id',
        'link',
    ];

    protected $casts = [
        'is_confirmed' => 'integer',
        'confirmed_at' => 'datetime',
    ];

    /**
     * @var array - links of menu contains badge
     */
    protected $badgeMenuLinks = [
        '/admin/be-partner-requests',
    ];

    protected static function boot()
    {
        parent::boot();
        BePartnerRequest::observe(BePartnerRequestObserver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curator()
    {
        return $this->belongsTo(User::class, 'curator_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quittance()
    {
        return $this->belongsTo(Quittance::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
