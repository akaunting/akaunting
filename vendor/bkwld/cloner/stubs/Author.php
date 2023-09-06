<?php namespace Bkwld\Cloner\Stubs;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Author extends Eloquent {

	public function articles() {
		return $this->belongsToMany('Bkwld\Cloner\Stubs\Article');
	}
}