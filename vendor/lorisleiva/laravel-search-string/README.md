# 🔍 Laravel Search String

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lorisleiva/laravel-search-string.svg)](https://packagist.org/packages/lorisleiva/laravel-search-string)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/lorisleiva/laravel-search-string/Tests?label=tests)](https://github.com/lorisleiva/laravel-search-string/actions?query=workflow%3ATests+branch%3Anext)
[![Total Downloads](https://img.shields.io/packagist/dt/lorisleiva/laravel-search-string.svg)](https://packagist.org/packages/lorisleiva/laravel-search-string)

Generates database queries based on one unique string using a simple and customizable syntax.

![Example of a search string syntax and its result](https://user-images.githubusercontent.com/3642397/40266921-6f7b4c70-5b54-11e8-8e40-000ae3b4e201.png)


## Introduction

Laravel Search String provides a simple solution for scoping your database queries using a human readable and customizable syntax. It will transform a simple string into a powerful query builder.

For example, the following search string will fetch the latest blog articles that are either not published or titled "My blog article".

```php
Article::usingSearchString('title:"My blog article" or not published sort:-created_at');

// Equivalent to:
Article::where('title', 'My blog article')
       ->orWhere('published', false)
       ->orderBy('created_at', 'desc');
```

This next example will search for the term "John" on the `customer` and `description` columns whilst making sure the invoices are either paid or archived.

```php
Invoice::usingSearchString('John and status in (Paid,Archived) limit:10 from:10');

// Equivalent to:
Invoice::where(function ($query) {
           $query->where('customer', 'like', '%John%')
               ->orWhere('description', 'like', '%John%');
       })
       ->whereIn('status', ['Paid', 'Archived'])
       ->limit(10)
       ->offset(10);
```

You can also query for the existence of related records, for example, articles published in 2020, which have more than 100 comments that are either not spam or written by John.

```php
Article::usingSearchString('published = 2020 and comments: (not spam or author.name = John) > 100');

// Equivalent to:
Article::where('published_at', '>=', '2020-01-01 00:00:00')
        ->where('published_at', '<=', '2020-12-31 23:59:59')
        ->whereHas('comments', function ($query) {
            $query->where('spam', false)
                ->orWhereHas('author' function ($query) {
                    $query->where('name', 'John');
                });
        }, '>', 100);
```

As you can see, not only it provides a convenient way to communicate with your Laravel API (instead of allowing dozens of query fields), it also can be presented to your users as a tool to explore their data.

## Installation

```bash
# Install via composer
composer require lorisleiva/laravel-search-string

# (Optional) Publish the search-string.php configuration file
php artisan vendor:publish --tag=search-string
```

## Basic usage

Add the `SearchString` trait to your models and configure the columns that should be used within your search string.

```php
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Article extends Model
{
    use SearchString;

    protected $searchStringColumns = [
        'title', 'body', 'status', 'rating', 'published', 'created_at',
    ];
}
```

Note that you can define these in [other parts of your code](#other-places-to-configure) and [customise the behaviour of each column](#configuring-columns).

That's it! Now you can create a database query using the search string syntax.

```php
Article::usingSearchString('title:"Hello world" sort:-created_at,published')->get();
```

## The search string syntax

Note that the spaces between operators don't matter.

### Exact matches

```php
'rating: 0'
'rating = 0'
'title: Hello'               // Strings without spaces do not need quotes
'title: "Hello World"'       // Strings with spaces require quotes
"title: 'Hello World'"       // Single quotes can be used too
'rating = 99.99'
'created_at: "2018-07-06 00:00:00"'
```

### Comparisons

```php
'title < B'
'rating > 3'
'created_at >= "2018-07-06 00:00:00"'
```

### Lists

```php
'title in (Hello, Hi, "My super article")'
'status in(Finished,Archived)'
'status:Finished,Archived'
```

### Dates

The column must either be cast as a date or explicitly marked as a date in the [column options](#date).

```php
// Year precision
'created_at >= 2020'                    // 2020-01-01 00:00:00 <= created_at
'created_at > 2020'                     // 2020-12-31 23:59:59 < created_at
'created_at = 2020'                     // 2020-01-01 00:00:00 <= created_at <= 2020-12-31 23:59:59
'not created_at = 2020'                 // created_at < 2020-01-01 00:00:00 and created_at > 2020-12-31 23:59:59

// Month precision
'created_at = 01/2020'                  // 2020-01-01 00:00:00 <= created_at <= 2020-01-31 23:59:59
'created_at <= "Jan 2020"'              // created_at <= 2020-01-31 23:59:59
'created_at < 2020-1'                   // created_at < 2020-01-01 00:00:00

// Day precision
'created_at = 2020-12-31'               // 2020-12-31 00:00:00 <= created_at <= 2020-12-31 23:59:59
'created_at >= 12/31/2020"'             // 2020-12-31 23:59:59 <= created_at
'created_at > "Dec 31 2020"'            // 2020-12-31 23:59:59 < created_at

// Hour and minute precisions
'created_at = "2020-12-31 16"'          // 2020-12-31 16:00:00 <= created_at <= 2020-12-31 16:59:59
'created_at = "2020-12-31 16:30"'       // 2020-12-31 16:30:00 <= created_at <= 2020-12-31 16:30:59
'created_at = "Dec 31 2020 5pm"'        // 2020-12-31 17:00:00 <= created_at <= 2020-12-31 17:59:59
'created_at = "Dec 31 2020 5:15pm"'     // 2020-12-31 17:15:00 <= created_at <= 2020-12-31 17:15:59

// Exact precision
'created_at = "2020-12-31 16:30:00"'    // created_at = 2020-12-31 16:30:00
'created_at = "Dec 31 2020 5:15:10pm"'  // created_at = 2020-12-31 17:15:10

// Relative dates
'created_at = today'                    // today between 00:00 and 23:59
'not created_at = today'                // any time before today 00:00 and after today 23:59
'created_at >= tomorrow'                // from tomorrow at 00:00
'created_at <= tomorrow'                // until tomorrow at 23:59
'created_at > tomorrow'                 // from the day after tomorrow at 00:00
'created_at < tomorrow'                 // until today at 23:59
```

### Booleans

The column must either be cast as a boolean or explicitly marked as a boolean in the [column options](#boolean).

Alternatively, if the column is marked as a date, it will automatically be marked as a boolean using `is null` and `is not null`.

```php
'published'         // published = true
'created_at'        // created_at is not null
```

### Negations

```php
'not title:Hello'
'not title="My super article"'
'not rating:0'
'not rating>4'
'not status in (Finished,Archived)'
'not published'     // published = false
'not created_at'    // created_at is null
```

### Null values

The term `NULL` is case sensitive.

```php
'body:NULL'         // body is null
'not body:NULL'     // body is not null
```

### Searchable

At least one column must be [defined as searchable](#searchable-1).

The queried term must not match a boolean column, otherwise it will be handled as a boolean query.

```php
'Apple'             // %Apple% like at least one of the searchable columns
'"John Doe"'        // %John Doe% like at least one of the searchable columns
'not "John Doe"'    // %John Doe% not like any of the searchable columns
```

### And/Or

```php
'title:Hello body:World'        // Implicit and
'title:Hello and body:World'    // Explicit and
'title:Hello or body:World'     // Explicit or
'A B or C D'                    // Equivalent to '(A and B) or (C and D)'
'A or B and C or D'             // Equivalent to 'A or (B and C) or D'
'(A or B) and (C or D)'         // Explicit nested priority
'not (A and B)'                 // Equivalent to 'not A or not B'
'not (A or B)'                  // Equivalent to 'not A and not B'
```

### Relationships

The column must be explicitly [defined as a relationship](#relationship) and the model associated with this relationship must also use the `SearchString` trait.

When making a nested query within a relationship, Laravel Search String will use the column definition of the related model.

In the following examples, `comments` is a `HasMany` relationship and `author` is a nested `BelongsTo` relationship within the `Comment` model.

```php
// Simple "has" check
'comments'                              // Has comments
'not comments'                          // Doesn't have comments
'comments = 3'                          // Has 3 comments
'not comments = 3'                      // Doesn't have 3 comments
'comments > 10'                         // Has more than 10 comments
'not comments <= 10'                    // Same as before
'comments <= 5'                         // Has 5 or less comments
'not comments > 5'                      // Same as before

// "WhereHas" check
'comments: (title: Superbe)'            // Has comments with the title "Superbe"
'comments: (not title: Superbe)'        // Has comments whose titles are different than "Superbe"
'not comments: (title: Superbe)'        // Doesn't have comments with the title "Superbe"
'comments: (quality)'                   // Has comments whose searchable columns match "%quality%"
'not comments: (spam)'                  // Doesn't have comments marked as spam
'comments: (spam) >= 3'                 // Has at least 3 spam comments
'not comments: (spam) >= 3'             // Has at most 2 spam comments
'comments: (not spam) >= 3'             // Has at least 3 comments that are not spam
'comments: (likes < 5)'                 // Has comments with less than 5 likes
'comments: (likes < 5) <= 10'           // Has at most 10 comments with less than 5 likes
'not comments: (likes < 5)'             // Doesn't have comments with less than 5 likes
'comments: (likes > 10 and not spam)'   // Has non-spam comments with more than 10 likes

// "WhereHas" shortcuts
'comments.title: Superbe'               // Same as 'comments: (title: Superbe)'
'not comments.title: Superbe'           // Same as 'not comments: (title: Superbe)'
'comments.spam'                         // Same as 'comments: (spam)'
'not comments.spam'                     // Same as 'not comments: (spam)'
'comments.likes < 5'                    // Same as 'comments: (likes < 5)'
'not comments.likes < 5'                // Same as 'not comments: (likes < 5)'

// Nested relationships
'comments: (author: (name: John))'      // Has comments from the author named John
'comments.author: (name: John)'         // Same as before
'comments.author.name: John'            // Same as before

// Nested relationships are optimised
'comments.author.name: John and comments.author.age > 21'   // Same as: 'comments: (author: (name: John and age > 21))
'comments.likes > 10 or comments.author.age > 21'           // Same as: 'comments: (likes > 10 or author: (age > 21))
```

Note that all these expressions delegate to the `has` query method. Therefore, it works out-of-the-box with the following relationship types: `HasOne`, `HasMany`, `HasOneThrough`, `HasManyThrough`, `BelongsTo`, `BelongsToMany`, `MorphOne`, `MorphMany` and `MorphToMany`.

The only relationship type currently not supported is `MorphTo` since Laravel Search String needs an explicit related model to use withing nested queries.

### Special keywords

Note that these keywords [can be customised](#configuring-special-keywords).

```php
'fields:title,body,created_at'  // Select only title, body, created_at
'not fields:rating'             // Select all columns but rating
'sort:rating,-created_at'       // Order by rating asc, created_at desc
'limit:1'                       // Limit 1
'from:10'                       // Offset 10
```

## Configuring columns

### Column aliases

If you want a column to be queried using a different name, you can define it as a key/value pair where the key is the database column name and the value is the alias you wish to use.

```php
protected $searchStringColumns = [
    'title',
    'body' => 'content',
    'published_at' => 'published',
    'created_at' => 'created',
];
```

You can also provide a regex pattern for a more flexible alias definition.

```php
protected $searchStringColumns = [
    'published_at' => '/^(published|live)$/',
    // ...
];
```

### Column options

You can configure a column even further by assigning it an array of options.

```php
protected $searchStringColumns = [
    'created_at' => [
        'key' => 'created',         // Default to column name: /^created_at$/
        'date' => true,             // Default to true only if the column is cast as date.
        'boolean' => true,          // Default to true only if the column is cast as boolean or date.
        'searchable' => false       // Default to false.
        'relationship' => false     // Default to false.
        'map' => ['x' => 'y']       // Maps data from the user input to the database values. Default to [].
    ],
    // ...
];
```

#### Key
The `key` option is what we've been configuring so far, i.e. the alias of the column. It can be either a regex pattern (therefore allowing multiple matches) or a regular string for an exact match.

#### Date
If a column is marked as a `date`, the value of the query will be parsed using `Carbon` whilst keeping the level of precision given by the user. For example, if the `created_at` column is marked as a `date`:

```php
'created_at >= tomorrow' // Equivalent to:
$query->where('created_at', '>=', 'YYYY-MM-DD 00:00:00');
// where `YYYY-MM-DD` matches the date of tomorrow.

'created_at = "July 6, 2018"' // Equivalent to:
$query->where('created_at', '>=', '2018-07-06 00:00:00');
      ->where('created_at', '<=', '2018-07-06 23:59:59');
```

By default any column that is cast as a date (using Laravel properties), will be marked as a date for LaravelSearchString. You can force a column to not be marked as a date by assigning `date` to `false`.

#### Boolean
If a column is marked as a `boolean`, it can be used with no operator or value. For example, if the `paid` column is marked as a `boolean`:

```php
'paid' // Equivalent to:
$query->where('paid', true);

'not paid' // Equivalent to:
$query->where('paid', false);
```

If a column is marked as both `boolean` and `date`, it will be compared to `null` when used as a boolean. For example, if the `published_at` column is marked as `boolean` and `date` and uses the `published` alias:

```php
'published' // Equivalent to:
$query->whereNotNull('published');

'not published_at' // Equivalent to:
$query->whereNull('published');
```

By default any column that is cast as a boolean or as a date (using Laravel properties), will be marked as a boolean. You can force a column to not be marked as a boolean by assigning `boolean` to `false`.

#### Searchable
If a column is marked as `searchable`, it will be used to match search queries, i.e. terms that are alone but are not booleans like `Apple Banana` or `"John Doe"`.

For example if both columns `title` and `description` are marked as `searchable`:

```php
'Apple Banana' // Equivalent to:
$query->where(function($query) {
          $query->where('title', 'like', '%Apple%')
                ->orWhere('description', 'like', '%Apple%');
      })
      ->where(function($query) {
          $query->where('title', 'like', '%Banana%')
                ->orWhere('description', 'like', '%Banana%');
      });

'"John Doe"' // Equivalent to:
$query->where(function($query) {
          $query->where('title', 'like', '%John Doe%')
                ->orWhere('description', 'like', '%John Doe%');
      });
```

If no searchable columns are provided, such terms or strings will be ignored.

#### Relationship

If a column is marked as a `relationship`, it will be used to query relationships.

The column name must match a valid relationship method on the model but, as usual, aliases can be created using the [`key` option](#key).

The model associated with that relationship method must also use the `SearchString` trait in order to nest relationship queries.

For example, say you have an Article Model and you want to query its related comments. Then, there must be a valid `comments` relationship method and the `Comment` model must itself use the `SearchString` trait.

```php
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Article extends Model
{
    use SearchString;

    protected $searchStringColumns = [
        'comments' => [
            'key' => '/^comments?$/',   // aliases the column to `comments` or `comment`.
            'relationship' => true,     // There must be a `comments` method that defines a relationship.
        ],
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class Comment extends Model
{
    use SearchString;

    protected $searchStringColumns = [
        // ...
    ];
}
```

Note that, since Laravel Search String is simply delegating to the `$builder->has(...)` method, you can provide any fancy relationship method you want and the constraints will be kept. For example:

```php
protected $searchStringColumns = [
    'myComments' => [
        'key' => 'my_comments',
        'relationship' => true,
    ],
];

public function myComments()
{
    return $this->hasMany(Comment::class)->where('author_id', Auth::user()->id);
}
```

## Configuring special keywords

You can customise the name of a keyword by defining a key/value pair within the `$searchStringKeywords` property.

```php
protected $searchStringKeywords = [
    'select' => 'fields',   // Updates the selected query columns
    'order_by' => 'sort',   // Updates the order of the query results
    'limit' => 'limit',     // Limits the number of results
    'offset' => 'from',     // Starts the results at a further index
];
```

Similarly to column values you can provide an array to define a custom `key` of the keyword. Note that the `date`, `boolean`, `searchable` and `relationship` options are not applicable for keywords.

```php
protected $searchStringKeywords = [
    'select' => [
        'key' => 'fields',
    ],
    // ...
];
```

## Other places to configure

As we've seen so far, you can configure your columns and special keywords using the `searchStringColumns` and `searchStringKeywords` properties on your model.

You can also override the `getSearchStringOptions` method on your model which defaults to:

```php
public function getSearchStringOptions()
{
    return [
        'columns' => $this->searchStringColumns ?? [],
        'keywords' => $this->searchStringKeywords ?? [],
    ];
}
```

If you'd rather not define any of these configurations on the model itself, you can define them directly on the `config/search-string.php` file like this:

```php
// config/search-string.php
return [
    'default' => [
        'keywords' => [ /* ... */ ],
    ],

    Article::class => [
        'columns'  => [ /* ... */ ],
        'keywords' => [ /* ... */ ],
    ],
];
```

When resolving the options for a particular model, LaravelSearchString will merge those configurations in the following order:
1. First using the configurations defined on the model
2. Then using the config file at the key matching the model class
3. Then using the config file at the `default` key
4. Finally using some fallback configurations

## Configuring case insensitive searches

When using databases like PostgreSql, you can override the default behavior of case sensitive searches by setting case_insensitive to true in your options amongst columns and keywords. For example, in the config/search-string.php

```php
return [
    'default' => [
        'case_insensitive' => true, // <- Globally.
        // ...
    ],

    Article::class => [
        'case_insensitive' => true, // <- Only for the Article class.
        // ...
    ],
];
```

When set to true, it will lowercase both the column and the value before comparing them using the like operator.

```
$value = mb_strtolower($value, 'UTF8');
$query->whereRaw("LOWER($column) LIKE ?", ["%$value%"]);
```


## Error handling

The provided search string can be invalid for numerous reasons.
- It does not comply to the search string syntax
- It tries to query an inexisting column or column alias
- It provides invalid values to special keywords like `limit`
- Etc.

Any of those errors will throw an `InvalidSearchStringException`.

However you can choose whether you want these exceptions to bubble up to the Laravel exception handler or whether you want them to fail silently. For that, you need to choose a fail strategy on your `config/search-string.php` configuration file:

```php
// config/search-string.php
return [
    'fail' => 'all-results', // (Default) Silently fail with a query containing everything.
    'fail' => 'no-results',  // Silently fail with a query containing nothing.
    'fail' => 'exceptions',  // Throw exceptions.

    // ...
];
```
