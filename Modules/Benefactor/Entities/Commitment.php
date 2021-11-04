<?php

namespace Modules\Benefactor\Entities;

use Auth;
use DB;
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



class Commitment extends BaseModel
{
    use HasHashedMediaTrait;
    use HasRoles;

    use HasFactory;
    use SoftDeletes;

    protected $table = "commitments";

    protected static $logName = 'commitments';
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
        return \Modules\Benefactor\Database\factories\CommitmentFactory::new();
    }

    public function donator(){
        return $this->belongsTo('Modules\Benefactor\Entities\Donator');
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


    /**
     * Get the list of all the Columns of the table.
     *
     * @return array Column names array
     */
    public function getTableColumns()
    {
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        
        switch($driver){
            case 'mysql':
                    $table_info_columns = DB::select(DB::raw('SHOW COLUMNS FROM '.$this->getTable()));
                break;
            case 'pgsql':       
                    $table_info_columns = DB::select(DB::raw(
                        "SELECT data_type as Type, column_name as Field
                            FROM information_schema.columns
                        Where table_schema = 'public'    
                        AND table_name   = '".$this->getTable()."'"
                    ));
                break;
        }   

        return $table_info_columns;
    }
}

