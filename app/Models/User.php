<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laratalks\Validator\Exceptions\ValidationException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Validators\UserValidator;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable , SoftDeletes , UserValidator;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const STATUS_APPROVED_NAME = 'Approved';
    const STATUS_PENDING_NAME = 'Pending';
    const STATUS_APPROVED = 1;
    const STATUS_PENDING = 0;

    CONST GENDER_MALE_NAME = 'Male';
    CONST GENDER_FEMALE_NAME = 'Female';
    CONST GENDER_MALE = 1;
    CONST GENDER_FEMALE = 0;

    CONST ROLE_USER = '0';
    CONST ROLE_ADMIN = '1';
    CONST ROLE_SUPER_ADMIN = '-1';

    CONST SCENARIO_CREATE = 'creating';

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public static function statuses()
    {
        return [
            self::STATUS_PENDING => self::STATUS_PENDING_NAME,
            self::STATUS_APPROVED => self::STATUS_APPROVED_NAME
        ];
    }

    public static function genders()
    {
        return [
            self::GENDER_MALE => self::GENDER_MALE_NAME,
            self::GENDER_FEMALE => self::GENDER_FEMALE_NAME
        ];
    }

    public function getStatus()
    {
        return self::statuses()[$this->status];
    }

    public function getGender()
    {
        return self::genders()[$this->gender];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function social()
    {
        return $this->hasOne( SocialAccount::class ,'user_id' ,'id');
    }

    /**
     * @param $model
     */
    public function beforeSave( $model )
    {
        $model->password = $model->password
            ? Hash::make($model->password)
            : Hash::make(Str::random(8));
    }

    public static function boot()
    {
        parent::boot();
        static::creating( function( $model ) {
            $model->beforeSave( $model );
            // Event::dispatch('user.creating', $model);
        });

        static::created( function( $model ) {
//            $model->afterSave( $model );
            // Event::dispatch('user.created', $model);
        });

        static::updating( function( $model ) {
//            Event::dispatch('user.updating', $model);
        });

        static::updated( function( $model ) {
//            Event::dispatch('user.updated', $model);
        });

        static::deleting( function( $model ) {
//            Event::dispatch('user.deleting', $model);
        });

        static::deleted( function( $model ) {
//            Event::dispatch('user.deleted', $model);
        });
    }
}
