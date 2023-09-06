<?php namespace Bkwld\Cloner\Stubs;

use Bkwld\Cloner\Cloneable as Cloneable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Photo extends Eloquent {
	use Cloneable;

	private $clone_exempt_attributes = ['uid', 'source'];
	private $cloneable_file_attributes = ['image'];

	public function article() {
		return $this->belongsTo('Bkwld\Cloner\Stubs\Article');
	}

	public function onCloning() {
		$this->uid = 2;
	}
}