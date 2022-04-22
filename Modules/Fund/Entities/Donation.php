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



class Donation extends Basemodel
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'donations';

    protected static $logName = 'donations';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'donation_id'];

    
    public function donator()
    {
        return $this->belongsTo('Modules\Benefactor\Entities\Donator')->with('user');
    }


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Fund\Database\Factories\DonationFactory::new();
    }
}
