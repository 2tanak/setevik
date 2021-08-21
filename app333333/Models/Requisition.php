<?php

namespace App\Models;

use App\User;
use App\Traits\Badgable;
use App\Observers\RequisitionObserver;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use Badgable;

    protected $fillable = [
        'user_id',
        'curator_id',
        'product_id',
        'requisition_type_id',
        'user_quittance_id',
        'curator_quittance_id',
        'is_confirmed',
        'is_cancelled',
    ];

    protected $casts = [
        'is_confirmed'          => 'boolean',
        'is_cancelled'          => 'boolean',
        'confirmed_at'          => 'datetime',
        'curator_confirmed_at'  => 'datetime',
    ];

    /**
     * @var array - links of menu contains badge
     */
    protected $badgeMenuLinks = [
        '/oss/requisitions',
    ];

    protected static function boot()
    {
        parent::boot();
        Requisition::observe(RequisitionObserver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requisitionType()
    {
        return $this->belongsTo(RequisitionType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userQuittance()
    {
        return $this->belongsTo(Quittance::class, 'user_quittance_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curatorQuittance()
    {
        return $this->belongsTo(Quittance::class, 'curator_quittance_id');
    }
}
