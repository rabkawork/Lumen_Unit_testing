<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model {

	 /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'histories';

	protected $guarded = [];

	protected function saveLog($data)
	{
		return History::insert($data);
	} 

}