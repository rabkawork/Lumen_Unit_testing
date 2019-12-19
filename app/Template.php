<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model {

	 /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'templates';

	protected $guarded = [];

	/**
     * Get the Checklist record associated with the user.
     */
    public function Checklist()
    {
        return $this->hasOne('App\Checklist');
    }


    /**
     * Get the Checklist record associated with the user.
     */
    public function Item()
    {
        return $this->hasMany('App\Item');
    }
}