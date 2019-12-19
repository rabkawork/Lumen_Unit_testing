<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model {

	 /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'checklists';

	protected $guarded = [];

	// public function project()
	// {
	// 	return $this->belongsTo("App\Project");
	// }

}