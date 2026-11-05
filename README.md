# PHP ActiveRecord (BACKBONE, s.r.o. fork)

`backbone/php-activerecord` is a fork of [PHP ActiveRecord](https://github.com/kla/php-activerecord)
by Kien La and Jacques Fuentes, maintained by BACKBONE, s.r.o. Upstream development stopped after 1.0 (2010).
This fork keeps the library running on current PHP versions (up to PHP 8.5) and contains a few deliberate
differences from upstream, listed [below](#differences-from-upstream).

See [CHANGELOG.md](CHANGELOG.md) for release notes.

## Introduction

A brief summarization of what ActiveRecord is:

> Active record is an approach to access data in a database. A database table or view is wrapped into a class,
> thus an object instance is tied to a single row in the table. After creation of an object, a new row is added to
> the table upon save. Any object loaded gets its information from the database; when an object is updated, the
> corresponding row in the table is also updated. The wrapper class implements accessor methods or properties for
> each column in the table or view.

More details can be found [here](http://en.wikipedia.org/wiki/Active_record_pattern).

This implementation is inspired and thus borrows heavily from Ruby on Rails' ActiveRecord.
We have tried to maintain their conventions while deviating mainly because of convenience or necessity.
Of course, there are some differences which will be obvious to the user if they are familiar with rails.

## Requirements

- PHP 7.1+ (used in production on PHP 8.1 - 8.5)
- PDO driver for your respective database

## Supported Databases

- MySQL / MariaDB
- SQLite
- PostgreSQL
- Oracle

## Features

- Finder methods
- Dynamic finder methods
- Writer methods
- Relationships
- Validations
- Callbacks
- Serializations (json/xml/csv/array)
- Transactions
- Caching of table schemas and models (Memcache, Memcached, APC)
- Support for multiple adapters
- Miscellaneous options such as: aliased/protected/accessible attributes

## Installation

Install with [Composer](https://getcomposer.org/):

    composer require backbone/php-activerecord:^1.1

The library's classes are autoloaded by Composer. Your model classes must be autoloadable by your application
(e.g. PSR-4 in your `composer.json`). Alternatively, include `lib/ActiveRecord.php`, which also registers an
autoloader for models in the directory given by `set_model_directory()` (disable it by defining
`PHP_ACTIVERECORD_AUTOLOAD_DISABLE`).

## Configuration

There are essentially only three configuration points you must concern yourself with:

1. Setting the model auto_load directory (only needed with the bundled model autoloader).
2. Configuring your database connections.
3. Setting the database connection to use for your environment.

Example:

    ActiveRecord\Config::initialize(function($cfg)
    {
        $cfg->set_model_directory('/path/to/your/model_directory');
        $cfg->set_connections(
            array(
                'development' => 'mysql://username:password@localhost/development_database_name?charset=utf8mb4',
                'test' => 'mysql://username:password@localhost/test_database_name',
                'production' => 'mysql://username:password@localhost/production_database_name'
            )
        );
    });

PHP ActiveRecord will default to use your development database. For testing or production, you simply set the default
connection according to your current environment ('test' or 'production'):

    ActiveRecord\Config::initialize(function($cfg)
    {
        $cfg->set_default_connection(your_environment);
    });

Once you have configured these three settings you are done. ActiveRecord takes care of the rest for you.
It does not require that you map your table schema to yaml/xml files. It will query the database for this information and
cache it so that it does not make multiple calls to the database for a single schema.

Other options of `ActiveRecord\Config`: `set_logging()` / `set_logger()` (SQL log including query duration),
`set_cache()` (schema and model caching), `set_date_format()` and `set_validator()` (custom validations class).

## Basic CRUD

### Retrieve
These are your basic methods to find and retrieve records from your database.

	$post = Post::find(1);
	echo $post->title; # 'My first blog post!!'
	echo $post->author_id; # 5

	# also the same since it is the first record in the db
	$post = Post::first();

	# finding using dynamic finders
	$post = Post::find_by_name('The Decider');
	$post = Post::find_by_name_and_id('The Bridge Builder',100);
	$post = Post::find_by_name_or_id('The Bridge Builder',100);

	# finding using a conditions array
	$posts = Post::find('all',array('conditions' => array('name=? or id > ?','The Bridge Builder',100)));

	# nothing found: unlike upstream, all of the above throw an exception
	try {
		$post = Post::find_by_name('Does not exist');
	} catch (ActiveRecord\RecordNotFound $e) {
		$post = null;
	}

### Create
Here we create a new post by instantiating a new object and then invoking the save() method.

	$post = new Post();
	$post->title = 'My first blog post!!';
	$post->author_id = 5;
	$post->save();
	# INSERT INTO `posts` (title,author_id) VALUES('My first blog post!!', 5)

### Update
To update you would just need to find a record first and then change one of its attributes.
It keeps an array of attributes that are "dirty" (that have been modified) and so our
sql will only update the fields modified.

	$post = Post::find(1);
	echo $post->title; # 'My first blog post!!'
	$post->title = 'Some real title';
	$post->save();
	# UPDATE `posts` SET title='Some real title' WHERE id=1

	$post->title = 'New real title';
	$post->author_id = 1;
	$post->save();
	# UPDATE `posts` SET title='New real title', author_id=1 WHERE id=1

### Delete
Deleting a record will not *destroy* the object. This means that it will call sql to delete
the record in your database but you can still use the object if you need to.

	$post = Post::find(1);
	$post->delete();
	# DELETE FROM `posts` WHERE id=1
	echo $post->title; # 'New real title'

## Differences from upstream

- `Model::find()` (`first`, `last`, `all`), `find_by_*` and `find_all_by_*` throw `ActiveRecord\RecordNotFound` when
  nothing is found; upstream returns `null` or an empty array. `find_or_create_by_*` creates the record instead.
- Lazy-loaded relationships (`belongs_to`, `has_one`, `has_many`) return `null` or an empty array when the related
  record is missing.
- An attribute is flagged dirty only when its value or type actually changes (`!==`), so `save()` does not update
  unchanged columns.
- `datetime_to_string()` produces `Y-m-d H:i:s` without a time zone suffix (accepted by MySQL/MariaDB in strict
  `sql_mode`).
- Query log entries include the query duration; a custom validations class can be set with `Config::set_validator()`.

## Branches and releases

| Branch        | Purpose                                                                  |
|---------------|--------------------------------------------------------------------------|
| `development` | development                                                              |
| `master`      | releases - changed only through pull requests from `development`, tagged |
| `legacy-php7` | the former `master` before PHP 8 support (no releases)                   |

Releases are git tags on `master` following semantic versioning and are published on
[Packagist](https://packagist.org/packages/backbone/php-activerecord). Require a version constraint such as `^1.1`
rather than a branch.

## Tests

The test suite in `test/` comes from upstream and is written for legacy PHPUnit (3.x, PEAR layout). It is not
runnable with current PHPUnit releases yet. Connection strings for the test databases are taken from the
`PHPAR_MYSQL`, `PHPAR_PGSQL`, `PHPAR_OCI` and `PHPAR_SQLITE` environment variables (see `test/helpers/config.php`).

## License

MIT, see [LICENSE](LICENSE). Original authors: Kien La and Jacques Fuentes.
