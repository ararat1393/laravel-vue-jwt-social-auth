<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as El_Model;

class Model extends El_Model
{

    public static function boot()
    {
        parent::boot();
        static::creating( function( $model ) {
            $model->beforeSave( $model );
        });
        static::created( function( $model ) {
            $model->afterSave( $model );
        });
        static::updating( function( $model ) {
            $model->beforeUpdate( $model );
        });
        static::updated( function( $model ) {
            $model->afterUpdate( $model );
        });
        static::deleting( function( $model ) {
            $model->beforeDelete( $model );
        });
        static::deleted( function( $model ) {
            $model->afterDelete( $model );
        });
    }

    /**
     * @param $model
     */
    public function beforeSave( $model ) {}

    /**
     * @param $model
     */
    public function afterSave( $model ) {}

    /**
     * @param $model
     */
    public function beforeUpdate( $model ) {}

    /**
     * @param $model
     */
    public function afterUpdate( $model ) {}

    /**
     * @param $model
     */
    public function beforeDelete( $model ) {}

    /**
     * @param $model
     */
    public function afterDelete( $model ) {}
}
