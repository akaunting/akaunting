<?php

// Deps
use Bkwld\Cloner\Cloner;
use Bkwld\Cloner\Adapters\Upchuck;
use Bkwld\Cloner\Stubs\Article;
use Bkwld\Cloner\Stubs\Author;
use Bkwld\Cloner\Stubs\Image;
use Bkwld\Cloner\Stubs\Photo;
use Bkwld\Cloner\Stubs\User;
use Bkwld\Upchuck\Helpers;
use Bkwld\Upchuck\Storage;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use League\Flysystem\Filesystem;
use League\Flysystem\Vfs\VfsAdapter as Adapter;
use League\Flysystem\MountManager;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VirtualFileSystem\FileSystem as Vfs;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    use MockeryPHPUnitIntegration;

	private $article;

	protected function initUpchuck()
	{

		// Setup filesystem
		$fs = new Vfs;
		$this->fs_path = $fs->path('/');
		$this->disk = new Filesystem(new Adapter($fs));

		// Create upchuck adapter instance

		$this->helpers = new Helpers([
			'url_prefix' => '/uploads/'
		]);

		$manager = new MountManager([
			'tmp' => $this->disk,
			'disk' => $this->disk,
		]);

		$storage = new Storage($manager, $this->helpers);

		$this->upchuck_adapter = new Upchuck(
			$this->helpers,
			$storage,
			$this->disk
		);
	}

	/**
	 * @return mixed
	 */
	protected function mockEvents()
	{
		return m::mock('Illuminate\Contracts\Events\Dispatcher', ['dispatch' => null]);
	}

	// https://github.com/laracasts/TestDummy/blob/master/tests/FactoryTest.php#L18
	protected function setUpDatabase()
	{
		$db = new DB;

		$db->addConnection([
			'driver' => 'sqlite',
			'database' => ':memory:'
		], 'default');

		$db->addConnection([
			'driver' => 'sqlite',
			'database' => ':memory:'
		], 'alt');

		$db->bootEloquent();
		$db->setAsGlobal();
	}

	// https://github.com/laracasts/TestDummy/blob/master/tests/FactoryTest.php#L31
	protected function migrateTables($connection = 'default')
	{
		DB::connection($connection)->getSchemaBuilder()->create('articles', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title');
			$table->timestamps();
		});

		DB::connection($connection)->getSchemaBuilder()->create('authors', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->timestamps();
		});

		DB::connection($connection)->getSchemaBuilder()->create('article_author', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('article_id')->unsigned();
			$table->bigInteger('author_id')->unsigned();
		});

		DB::connection($connection)->getSchemaBuilder()->create('photos', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('article_id')->unsigned();
			$table->string('uid');
			$table->string('image');
			$table->boolean('source')->nullable();
			$table->timestamps();
		});

		DB::connection($connection)->getSchemaBuilder()->create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->timestamps();
		});

		DB::connection($connection)->getSchemaBuilder()->create('article_user', function (Blueprint $table) {
			$table->bigInteger('rating')->unsigned();
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('article_id')->unsigned();
			$table->timestamps();
		});
	}

	protected function seed()
	{
		Article::unguard();
		$this->article = Article::create([
			'title' => 'Test',
		]);

		Author::unguard();
		$this->article->authors()->attach(Author::create([
			'name' => 'Steve',
		]));

		$this->article->ratings()->attach(User::create([
			'name' => 'Peter'
		]), [
			'rating' => 4
		]);

		$this->disk->write('test.jpg', 'contents');

		Photo::unguard();
		$this->article->photos()->save(new Photo([
			'uid' => 1,
			'image' => '/uploads/test.jpg',
			'source' => true,
		]));
	}

	// Test that a record is created in the same database
	function testExists()
	{
		$this->initUpchuck();
		$this->setUpDatabase();
		$this->migrateTables();
		$this->seed();

		// Wait 1.5 seconds to be able to detect a difference in the timestamps
		usleep(1500000);

		$cloner = new Cloner($this->upchuck_adapter, $this->mockEvents());
		$clone = $cloner->duplicate($this->article);

		// Test that the new article was created
		$this->assertTrue($clone->exists);
		$this->assertEquals(2, $clone->id);
		$this->assertEquals('Test', $clone->title);

		// Test if the timestamps have been reset
		$this->assertNotNull($clone->created_at);
		$this->assertTrue($this->article->created_at->lt($clone->created_at));
		$this->assertNotNull($clone->updated_at);
		$this->assertTrue($this->article->created_at->lt($clone->updated_at));

		// Test many to many
		$this->assertEquals(1, $clone->authors()->count());
		$this->assertEquals('Steve', $clone->authors()->first()->name);
		$this->assertEquals(2, DB::table('article_author')->count());

		// Test many to many with pivot
		$this->assertEquals(1, $clone->ratings()->count());
		$this->assertEquals('Peter', $clone->ratings()->first()->name);
		$this->assertEquals(2, DB::table('article_user')->count());

		// Test if the timestamps in the pivot table have been reset
		$this->assertNotNull($this->article->ratings()->first()->pivot->created_at);
		$this->assertNotNull($clone->ratings()->first()->pivot->created_at);
		$this->assertTrue($this->article->ratings()->first()->pivot->created_at->lt($clone->ratings()->first()->pivot->created_at));
		$this->assertNotNull($this->article->ratings()->first()->pivot->updated_at);
		$this->assertNotNull($clone->ratings()->first()->pivot->updated_at);
		$this->assertTrue($this->article->ratings()->first()->pivot->updated_at->lt($clone->ratings()->first()->pivot->updated_at));

		// Test one to many
		$this->assertEquals(1, $clone->photos()->count());
		$photo = $clone->photos()->first();

		// Test excemptions
		$this->assertNull($photo->source);

		// Test callbacks
		$this->assertNotEquals(1, $photo->uid);

		// Test the file was created in a different place
		$this->assertNotEquals('/uploads/test.jpg', $photo->image);

		// Test that the file is the same
		$path = $this->helpers->path($photo->image);
		$this->assertTrue($this->disk->has($path));
		$this->assertEquals('contents', $this->disk->read($path));

		// Test one to many inverse (BelongsTo)
        $image = Image::first();
        $clone = $cloner->duplicate($image);
        $this->assertNotNull($clone->article);
        $this->assertNotEquals($this->article->id, $clone->article->id);
    }

	// Test that model is created in a differetnt database.  These checks don't
	// use eloquent because Laravel has issues with relationships on models in
	// a different connection
	// https://github.com/laravel/framework/issues/9355
	function testExistsInAltDatabaseAndFilesystem()
	{
		$this->initUpchuck();
		$this->setUpDatabase();
		$this->migrateTables();
		$this->migrateTables('alt');
		$this->seed();

		// ADd the remote disk to upchuck adapter
		$this->remoteDisk = new Filesystem(new Adapter(new Vfs));
		$this->upchuck_adapter->setDestination($this->remoteDisk);

		// Make sure that the alt databse is empty
		$this->assertEquals(0, DB::connection('alt')->table('articles')->count());
		$this->assertEquals(0, DB::connection('alt')->table('authors')->count());
		$this->assertEquals(0, DB::connection('alt')->table('photos')->count());

		$cloner = new Cloner($this->upchuck_adapter, $this->mockEvents());
		$clone = $cloner->duplicateTo($this->article, 'alt');

		// Test that the new article was created
		$this->assertEquals(1, DB::connection('alt')->table('articles')->count());
		$clone = DB::connection('alt')->table('articles')->first();
		$this->assertEquals(1, $clone->id);
		$this->assertEquals('Test', $clone->title);

		// Test that many to many failed
		$this->assertEquals(0, DB::connection('alt')->table('authors')
			->where('article_id', $clone->id)->count());

		// Test one to many
		$this->assertEquals(1, DB::connection('alt')->table('photos')
			->where('article_id', $clone->id)->count());
		$photo = DB::connection('alt')->table('photos')
			->where('article_id', $clone->id)->first();

		// Test excemptions
		$this->assertNull($photo->source);

		// Test callbacks
		$this->assertNotEquals(1, $photo->uid);

		// Test the file was created on the remote disk
		$path = $this->helpers->path($photo->image);
		$this->assertTrue($this->remoteDisk->has($path));

		// Test that the file is the same
		$this->assertEquals('contents', $this->remoteDisk->read($path));
	}
}
