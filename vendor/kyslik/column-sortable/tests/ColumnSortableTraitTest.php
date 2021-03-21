<?php /** @noinspection PhpUnhandledExceptionInspection */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

/**
 * Class ColumnSortableTraitTest
 */
class ColumnSortableTraitTest extends \Orchestra\Testbench\TestCase
{

    /**
     * @var \User
     */
    private $user;

    /**
     * @var \Profile
     */
    private $profile;

    /**
     * @var \Comment
     */
    private $comment;

    /**
     * @var \Post
     */
    private $post;

    /**
     * @var
     */
    private $configDefaultDirection;


    /**
     * Method setUp() runs before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        app()->register(\Kyslik\ColumnSortable\ColumnSortableServiceProvider::class);

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        $this->user    = new User();
        $this->profile = new Profile();
        $this->comment = new Comment();
        $this->post    = new Post();

        $this->configDefaultDirection = 'asc';
    }


    public function testSortableWithoutDefaultAndRequestParameters()
    {
        config()->set('columnsortable.default_first_column', false);
        $query = $this->user->scopeSortable($this->user->newQuery());
        $this->assertEmpty($query->getQuery()->orders);

        config()->set('columnsortable.default_first_column', true);
        $this->user->sortable = ['name', 'id'];

        $query = $this->user->scopeSortable($this->user->newQuery());
        $this->assertEquals([
            [
                'column'    => 'users.'.Arr::first($this->user->sortable),
                'direction' => $this->configDefaultDirection,
            ],
        ], $query->getQuery()->orders);

        $query = $this->post->scopeSortable($this->post->newQuery());
        $this->assertEmpty($query->getQuery()->orders);
    }


    public function testSortableWithRequestParameters()
    {
        config()->set('columnsortable.default_first_column', false);

        $usersTable = $this->user->getTable();
        Request::replace(['sort' => 'name', 'direction' => 'asc']);
        $resultArray = $this->user->scopeSortable($this->user->newQuery())->getQuery()->orders;
        $expected    = ['column' => $usersTable.'.name', 'direction' => 'asc'];
        $this->assertEquals($expected, head($resultArray));

        Request::replace(['sort' => 'name', 'direction' => 'desc']);
        $resultArray = $this->user->scopeSortable($this->user->newQuery())->getQuery()->orders;
        $expected    = ['column' => $usersTable.'.name', 'direction' => 'desc'];
        $this->assertEquals($expected, head($resultArray));

        Request::replace(['sort' => 'name', 'direction' => '']);
        $result = $this->user->scopeSortable($this->user->newQuery())->getQuery()->orders;
        $this->assertNull($result);

        Request::replace(['sort' => '', 'direction' => 'asc']);
        $result = $this->user->scopeSortable($this->user->newQuery())->getQuery()->orders;
        $this->assertNull($result);

        Request::replace(['sort' => '', 'direction' => '']);
        $result = $this->user->scopeSortable($this->user->newQuery())->getQuery()->orders;
        $this->assertNull($result);

        Request::replace(['sort' => 'name']);
        $result = $this->user->scopeSortable($this->user->newQuery())->getQuery()->orders;
        $this->assertNull($result);

        Request::replace(['sort' => '']);
        $result = $this->user->scopeSortable($this->user->newQuery())->getQuery()->orders;
        $this->assertNull($result);
    }


    public function testSortableWithDefaultAndWithoutRequestParameters()
    {
        $usersTable = $this->user->getTable();
        $default    = [
            'name' => 'desc',
        ];

        $resultArray = $this->user->scopeSortable($this->user->newQuery(), $default)->getQuery()->orders;
        $expected    = ['column' => $usersTable.'.name', 'direction' => 'desc'];
        $this->assertEquals($expected, head($resultArray));
    }


    public function testSortableQueryJoinBuilder()
    {
        $query         = $this->user->newQuery()->with(['profile']);
        $relation      = $query->getRelation('profile');
        $resultQuery   = $this->invokeMethod($this->user, 'queryJoinBuilder', [$query, $relation]);
        $expectedQuery = $this->user->newQuery()->select('users.*')->leftJoin('profiles', 'users.id', '=', 'profiles.user_id');
        $this->assertEquals($expectedQuery->toSql(), $resultQuery->toSql());

        $query         = $this->profile->newQuery()->with(['user']);
        $relation      = $query->getRelation('user');
        $resultQuery   = $this->invokeMethod($this->user, 'queryJoinBuilder', [$query, $relation]);
        $expectedQuery = $this->profile->newQuery()->select('profiles.*')->leftJoin('users', 'profiles.user_id', '=', 'users.id');
        $this->assertEquals($expectedQuery->toSql(), $resultQuery->toSql());

        $query         = $this->comment->newQuery()->with(['parent']);
        $relation      = $query->getRelation('parent');
        $resultQuery   = $this->invokeMethod($this->comment, 'queryJoinBuilder', [$query, $relation]);
        $expectedQuery = $this->comment->newQuery()->from('comments as parent_comments')->select('parent_comments.*')
                                       ->leftJoin('comments', 'parent_comments.parent_id', '=', 'comments.id');
        $this->assertEquals($expectedQuery->toSql(), $resultQuery->toSql());
    }


    public function testSortableOverridingQueryOrderBuilder()
    {
        $sortParameters = ['sort' => 'address', 'direction' => 'desc'];
        $query          = $this->user->newQuery();
        $resultQuery    = $this->invokeMethod($this->user, 'queryOrderBuilder', [$query, $sortParameters]);
        $expectedQuery  = $this->user->newQuery()->join('profiles', 'users.id', '=', 'profiles.user_id')->orderBy('address', 'desc')
                                     ->select('users.*');
        $this->assertEquals($expectedQuery, $resultQuery);
    }


    public function testSortableOverridingQueryOrderBuilderOnRelation()
    {
        $sortParameters = ['sort' => 'profile.composite', 'direction' => 'desc'];
        $query          = $this->user->newQuery();

        $resultQuery = $this->invokeMethod($this->user, 'queryOrderBuilder', [$query, $sortParameters]);

        $expectedQuery =
            $this->user->newQuery()->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')->orderBy('phone', 'desc')
                       ->orderBy('address', 'desc')->select('users.*');

        $this->assertEquals($expectedQuery, $resultQuery);
    }


    public function testSortableAs()
    {
        $sortParameters = ['sort' => 'nick_name', 'direction' => 'asc'];
        $query          = $this->user->newQuery()->select('name as nick_name');
        $resultQuery    = $this->invokeMethod($this->user, 'queryOrderBuilder', [$query, $sortParameters]);
        $expectedQuery  = $this->user->newQuery()->select('name as nick_name')->orderBy('nick_name', 'asc');
        $this->assertEquals($expectedQuery, $resultQuery);
    }


    public function testSortableQueryJoinBuilderThrowsException()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(0);
        $query    = $this->user->hasMany(Profile::class)->newQuery();
        $relation = $query->getRelation('profile');
        $this->invokeMethod($this->user, 'queryJoinBuilder', [$query, $relation]);
    }


    /**
     * This test might be useless, because testFormatToSortParameters() does same
     */
    public function testSortableWithDefaultUsesConfig()
    {
        $usersTable = $this->user->getTable();
        $default    = 'name';

        $resultArray = $this->user->scopeSortable($this->user->newQuery(), $default)->getQuery()->orders;
        $expected    = ['column' => $usersTable.'.name', 'direction' => $this->configDefaultDirection];
        $this->assertEquals($expected, head($resultArray));

        $default = ['name'];

        $resultArray = $this->user->scopeSortable($this->user->newQuery(), $default)->getQuery()->orders;
        $expected    = ['column' => $usersTable.'.name', 'direction' => $this->configDefaultDirection];
        $this->assertEquals($expected, head($resultArray));
    }


    public function testParseParameters()
    {
        $array       = [];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = [null, null];
        $this->assertEquals($expected, $resultArray);

        $array       = ['sort' => ''];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = [null, null];
        $this->assertEquals($expected, $resultArray);

        $array       = ['direction' => ''];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = [null, null];
        $this->assertEquals($expected, $resultArray);

        $array       = ['direction' => 'foo'];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = [null, null];
        $this->assertEquals($expected, $resultArray);

        $array       = ['sort' => 'foo', 'direction' => ''];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = ['foo', $this->configDefaultDirection];
        $this->assertEquals($expected, $resultArray);

        $array       = ['sort' => 'foo', 'direction' => 'desc'];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = ['foo', 'desc'];
        $this->assertEquals($expected, $resultArray);

        $array       = ['sort' => 'foo', 'direction' => 'asc'];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = ['foo', 'asc'];
        $this->assertEquals($expected, $resultArray);

        $array       = ['sort' => 'foo', 'direction' => 'bar'];
        $resultArray = $this->invokeMethod($this->user, 'parseParameters', [$array]);
        $expected    = ['foo', $this->configDefaultDirection];
        $this->assertEquals($expected, $resultArray);
    }


    public function testFormatToParameters()
    {
        $array       = [];
        $resultArray = $this->invokeMethod($this->user, 'formatToParameters', [$array]);
        $expected    = [];
        $this->assertEquals($expected, $resultArray);

        $array       = null;
        $resultArray = $this->invokeMethod($this->user, 'formatToParameters', [$array]);
        $expected    = [];
        $this->assertEquals($expected, $resultArray);

        $array       = 'foo';
        $resultArray = $this->invokeMethod($this->user, 'formatToParameters', [$array]);
        $expected    = ['sort' => 'foo', 'direction' => $this->configDefaultDirection];
        $this->assertEquals($expected, $resultArray);

        $array       = ['foo'];
        $resultArray = $this->invokeMethod($this->user, 'formatToParameters', [$array]);
        $expected    = ['sort' => 'foo', 'direction' => $this->configDefaultDirection];
        $this->assertEquals($expected, $resultArray);

        $array       = ['foo' => 'desc'];
        $resultArray = $this->invokeMethod($this->user, 'formatToParameters', [$array]);
        $expected    = ['sort' => 'foo', 'direction' => 'desc'];
        $this->assertEquals($expected, $resultArray);

        $array       = ['foo' => 'desc', 'bar' => 'asc'];
        $resultArray = $this->invokeMethod($this->user, 'formatToParameters', [$array]);
        $expected    = ['sort' => 'foo', 'direction' => 'desc'];
        $this->assertEquals($expected, $resultArray);
    }


    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws \ReflectionException
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}

/**
 * Class User
 */
class User extends Model
{

    use \Kyslik\ColumnSortable\Sortable;

    /**
     * @var array
     */
    public $sortable = [
        'id',
        'name',
        'amount',
    ];

    public $sortableAs = [
        'nick_name',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }


    public function addressSortable($query, $direction)
    {
        return $query->join('profiles', 'users.id', '=', 'profiles.user_id')->orderBy('address', $direction)->select('users.*');
    }
}

/**
 * Class Profile
 */
class Profile extends Model
{

    /**
     * @var array
     */
    public $sortable = [
        'phone',
        'address',
        'composite',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function compositeSortable($query, $direction)
    {
        return $query->orderBy('phone', $direction)->orderBy('address', $direction);
    }
}

/**
 * Class Comment
 */
class Comment extends Model
{

    use \Kyslik\ColumnSortable\Sortable;

    /**
     * @var array
     */
    public $sortable = [
        'body',
        'created_at',
        'updated_at',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}

/**
 * Class Post
 */
class Post extends Model
{

    use \Kyslik\ColumnSortable\Sortable;
}
