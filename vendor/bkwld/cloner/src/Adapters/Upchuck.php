<?php namespace Bkwld\Cloner\Adapters;

// Deps
use Bkwld\Cloner\AttachmentAdapter;
use Bkwld\Upchuck\Helpers;
use Bkwld\Upchuck\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;

/**
 * File attachment adpater for https://github.com/BKWLD/upchuck
 */
class Upchuck implements AttachmentAdapter {

	/**
	 * @var \Bkwld\Upchuck\Helpers
	 */
	private $helpers;

	/**
	 * @var \Bkwld\Upchuck\Storage
	 */
	private $storage;

	/**
	 * @var \League\Flysystem\Filesystem
	 */
	private $disk;

	/**
	 * @var \League\Flysystem\MountManager
	 */
	private $disks;

	/**
	 * DI
	 */
	public function __construct(Helpers $helpers,
		Storage $storage,
		Filesystem $disk) {
		$this->helpers = $helpers;
		$this->storage = $storage;
		$this->disk = $disk;
	}

	/**
	 * Duplicate a file given it's URL
	 *
	 * @param  string $url
	 * @param  \Illuminate\Database\Eloquent\Model $clone
	 * @return string
	 */
	public function duplicate($url, $clone) {

		// Make the destination path
		$current_path = $this->helpers->path($url);
		$filename = basename($current_path);
		$dst_disk = $this->disks ? $this->disks->getFilesystem('dst') : $this->disk;
		$new_path = $this->storage->makeNestedAndUniquePath($filename, $dst_disk);

		// Copy, supporting alternative destination disks
		if ($this->disks) $this->disks->copy('src://'.$current_path, 'dst://'.$new_path);
		else $this->disk->copy($current_path, $new_path);

		// Return the Upchuck URL
		return $this->helpers->url($new_path);
	}

	/**
	 * Set a different destination for cloned items.  In doing so, create a
	 * MountManager instance that will be used to do the copying
	 *
	 * @param  \League\Flysystem\Filesystem $dst
	 */
	public function setDestination(Filesystem $dst) {
		$this->disks = new MountManager([
			'src' => $this->disk,
			'dst' => $dst,
		]);
	}

}
