<?php

namespace App;

use App\Models\Link;
use App\Models\Sale;
use App\Models\Wallet;
use App\Models\Status;
use App\Models\Cabinet;
use App\Models\Country;
use App\Models\Package;
use App\Models\Curation;
use App\Models\Activity;
use App\Models\Requisition;
use App\Models\Registration;
use App\Models\Subscription;
use App\Observers\UserObserver;
use App\Models\BePartnerRequest;
use App\Models\Trees\BinaryTreeNode;
use App\Traits\HasRolesAndPermissions;
use App\Services\Oss\TreeService as OssTreeService;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Carbon\Carbon;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable, HasRolesAndPermissions;

    protected $fillable = [
        'name',
        'last_name',
        'login',
        'email',
        'password',
        'birthday',
        'phone',
        'photo',
        'city',
        'country_id',
        'cabinet_id',
        'package_id',
        'status_id',
        'tree_node_id',
        'tree_inviter_node_id',
        'is_active', //
        'is_qualified',
        'is_oss_ever', // todo: rename later (meaning: if user bought OSS product ever)
        'has_activity_sib',
        'has_activity_oss',
        'is_wizard_activated',
        'is_female',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birthday'              => 'date',
        'activated_at'          => 'datetime',
        'oss_activated_at'      => 'datetime',
        'sib_registered_at'     => 'datetime',
        'oss_registered_at'     => 'datetime',
        'is_blocked'            => 'boolean',
        'is_active'             => 'boolean',
        'is_qualified'          => 'boolean',
        'has_activity'          => 'boolean',
        'has_activity_sib'      => 'boolean',
        'has_activity_oss'      => 'boolean',
        'is_wizard_activated'   => 'boolean',
        'is_female'             => 'boolean',
        'is_oss_ever'           => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        User::observe(UserObserver::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * @deprecated
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function curations()
    {
        return $this->hasMany(Curation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class)->withDefault([
            'code' => '',
            'name' => '',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class)->withDefault([
            'code'          => '',
            'name'          => 'Без статуса',
            'short_name'    => '',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getActive()
    {
		
		
		if($this->cabinet_id == 2 && $this->has_activity_sib == 1){
		return true;
		}else{
		    return false;
		}
        
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expectedwallets()
    {
        return $this->hasMany(ExpectedWallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bePartnerRequest()
    {
        return $this->hasOne(BePartnerRequest::class);
    }

    public function isAdmin()
    {
        return (bool) $this->roles->where('slug', 'admin')->count();
    }

    public function isActivated()
    {
        if ($this->isPartner()) {
            return $this->is_active && $this->activated_at;
        } elseif ($this->isResident()) {
            return $this->is_active && $this->oss_activated_at;
        }
        return false;
    }

    public function isPartner()
    {
        return $this->cabinet_id == Cabinet::SIB;
    }

    public function isResident()
    {
        return $this->cabinet_id == Cabinet::OSS;
    }

    public function getFullName()
    {
        return sprintf('%s %s', $this->name, $this->last_name);
    }

    public function getPublicId()
    {
        return str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    public function hasActivityAsPartner()
    {
        return $this->has_activity_sib;
    }

    public function hasActivityAsResident()
    {
        return $this->has_activity_oss;
    }

    public function getNodePartner()
    {
        return BinaryTreeNode::find($this->tree_node_id);
    }

    public function getCuratorNodePartner()
    {
        //return BinaryTreeNode::findOrFail($this->tree_inviter_node_id);
        return BinaryTreeNode::find($this->tree_inviter_node_id);
    }

    public function getCuratorPartner()
    {
        return $this->getCuratorNodePartner()->user;
    }

    public function getCuratorResident()
    {
        return (new OssTreeService())->getActiveCurator($this);
    }
}
