<?php

namespace Modules\Fund\Entities;

use Auth;
use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Traits\HasHashedMediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as UserModel;
use Spatie\Permission\Traits\HasRoles;



class Donation extends UserModel implements HasMedia
{
    use HasHashedMediaTrait;
    use HasRoles;

    use HasFactory;
    use SoftDeletes;

    protected $table = "donations";

    protected static $logName = 'donations';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];
    
    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    protected $hidden = [
     'password', 'remember_token',
    ];
    
    protected static function boot()
    {
        parent::boot();

        // create a event to happen on creating
        static::creating(function ($table) {
            $table->created_by = Auth::id();
            $table->created_at = Carbon::now();
        });

        // create a event to happen on updating
        static::updating(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on saving
        static::saving(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on deleting
        static::deleting(function ($table) {
            $table->deleted_by = Auth::id();
            $table->save();
        });
    }
    
    public function getAuthPassword()
    {
     return $this->password;
    }

    protected static function newFactory()
    {
        return \Modules\Fund\Database\factories\DonationFactory::new();
    }

    /**
     * Create Converted copies of uploaded images.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(250)
              ->height(250)
              ->quality(70);

        $this->addMediaConversion('thumb300')
              ->width(300)
              ->height(300)
              ->quality(70);
    }
}
