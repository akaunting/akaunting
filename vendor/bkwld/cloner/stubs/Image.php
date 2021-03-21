<?php namespace Bkwld\Cloner\Stubs;

use Bkwld\Cloner\Cloneable as Cloneable;

class Image extends Photo {
	use Cloneable;

    public $cloneable_relations = ['article'];

	protected $table = 'photos';

    public function onCloning() {
        $this->uid = 2;
    }
}
