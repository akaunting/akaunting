<?php

namespace Bkwld\Cloner\Stubs;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent {

	public function rated_articles() {
		return $this->belongsToMany(Article::class)->withPivot('rating')->withTimestamps();
	}
	
}