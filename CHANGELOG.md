# CHANGELOG

## Instructions

Fork of [kla/php-activerecord](https://github.com/kla/php-activerecord) maintained by BACKBONE, s.r.o. as
`backbone/php-activerecord`. Entries before the fork are kept as written upstream.

| Branch        | Purpose                                                                  |
|---------------|--------------------------------------------------------------------------|
| `development` | development                                                              |
| `master`      | releases - changed only through pull requests from `development`, tagged |
| `legacy-php7` | the former `master` before PHP 8 support (no releases)                   |

Every release is a git tag on `master` (semantic versioning). Require a tag in your application's `composer.json`
(e.g. `"backbone/php-activerecord": "^1.1"`), not a branch.

## Releases

### 1.1.1 (05.11.2026)
 - **Fixed**: `validates_uniqueness_of` quotes column names, including the primary key, the same way `SQLBuilder`
   quotes hash conditions. A column named with a reserved word (`key`, `order`) used to fail with SQL error 1064 on
   MySQL/MariaDB as soon as the record was saved. Ported from upstream `99904dc`, which existed only on
   `legacy-php7`; adds a test with a `valuestore` table for mysql/pgsql/sqlite/oci
   ([`db6134b`](https://github.com/backbonesk/php-activerecord/commit/db6134b))

### 1.1.0 (05.11.2026)
First release with PHP 8.x support (8.1 to 8.5); the minimum PHP version is 7.1. Contains all changes made in the
fork since 1.0 (the `php82` branch, formerly `dev`).

#### Differences from upstream (since 2013)
 - **⚠️ Breaking**: `Model::find()` (`first`, `last`, `all`) as well as `find_by_*` / `find_all_by_*` throw
   `RecordNotFound` when nothing is found (upstream returns `null` / an empty array); `find_or_create_by_*` catches
   the exception and creates the record ([`8bb4966`](https://github.com/backbonesk/php-activerecord/commit/8bb4966))
 - **Changed**: lazy-loaded relationships (`belongs_to`, `has_one`, `has_many`) return `null` / an empty array
   instead of throwing when the related record is missing
   ([`8bb4966`](https://github.com/backbonesk/php-activerecord/commit/8bb4966),
   [`f693bcb`](https://github.com/backbonesk/php-activerecord/commit/f693bcb))
 - **Changed**: PSR-0 file layout (`lib/ActiveRecord`), Composer autoloading
   ([`c5acfad`](https://github.com/backbonesk/php-activerecord/commit/c5acfad))
 - **Changed**: package renamed to `backbone/php-activerecord`
   ([`132b0d6`](https://github.com/backbonesk/php-activerecord/commit/132b0d6))
 - **Added**: query profiling - the log includes each query's duration
   ([`13c7a24`](https://github.com/backbonesk/php-activerecord/commit/13c7a24))
 - **Added**: custom validations class via `Config::set_validator()`
   ([`4796087`](https://github.com/backbonesk/php-activerecord/commit/4796087))
 - **Added**: `isset($model->x)` also tries magic getters
   ([`e4c3f89`](https://github.com/backbonesk/php-activerecord/commit/e4c3f89))
 - **Fixed**: a wrong class name in a relationship (Reflection) ends with a readable error
   ([`75eb052`](https://github.com/backbonesk/php-activerecord/commit/75eb052))

#### Fixes
 - **Fixed**: `datetime_to_string()` no longer appends the time zone (`Y-m-d H:i:s`) - MariaDB/MySQL in strict
   `sql_mode` rejected values with `T` (error 1292)
   ([`54c3939`](https://github.com/backbonesk/php-activerecord/commit/54c3939))
 - **Fixed**: loading `has_many` relationships with `through`
   ([`3a21b5c`](https://github.com/backbonesk/php-activerecord/commit/3a21b5c))
 - **Fixed**: `PgsqlAdapter` no longer uses `pg_attrdef.adsrc` (removed in PostgreSQL 12)
   ([`9985b94`](https://github.com/backbonesk/php-activerecord/commit/9985b94))
 - **Fixed**: `find_by_pk()` with a scalar value (PHP 8: `count()` on a scalar)
   ([`6b534d5`](https://github.com/backbonesk/php-activerecord/commit/6b534d5))
 - **Fixed**: an attribute is flagged dirty only when its value or type changes (`!==`)
   ([`78eb892`](https://github.com/backbonesk/php-activerecord/commit/78eb892))
 - **Fixed**: an attribute is flagged dirty only when its value actually changes
   ([`00325f2`](https://github.com/backbonesk/php-activerecord/commit/00325f2),
   [`f5d94d5`](https://github.com/backbonesk/php-activerecord/commit/f5d94d5))
 - **Fixed**: `DateTime` compatibility with PHP 7.1
   ([`401f4d3`](https://github.com/backbonesk/php-activerecord/commit/401f4d3))

#### PHP compatibility
 - **Changed**: PHP 8.5 - `{}` property interpolation in strings, `static::` callables, undeclared `$klass`,
   `fputcsv` escape, `strlen(null)` in `Utils`/`Expressions`, `is_hash`
   ([`cced8d0`](https://github.com/backbonesk/php-activerecord/commit/cced8d0))
 - **Changed**: PHP 8.4 - explicit nullable types, `AllowDynamicProperties`, `Errors::getIterator` return type
   ([`99f34ff`](https://github.com/backbonesk/php-activerecord/commit/99f34ff),
   [`65f4768`](https://github.com/backbonesk/php-activerecord/commit/65f4768),
   [`c43985c`](https://github.com/backbonesk/php-activerecord/commit/c43985c),
   [`d8a7d0c`](https://github.com/backbonesk/php-activerecord/commit/d8a7d0c))
 - **Changed**: PHP 8.0/7.4 deprecations - `implode`, `spl_autoload_register`, `(double)`
   ([`9046a5d`](https://github.com/backbonesk/php-activerecord/commit/9046a5d),
   [`756eb65`](https://github.com/backbonesk/php-activerecord/commit/756eb65),
   [`a3243e3`](https://github.com/backbonesk/php-activerecord/commit/a3243e3))
 - **Changed**: PHP 8.2+ - `AllowDynamicProperties`
   ([`a120b4a`](https://github.com/backbonesk/php-activerecord/commit/a120b4a))
 - **Changed**: PHP 8.2 ([`268e441`](https://github.com/backbonesk/php-activerecord/commit/268e441) to
   [`6f96726`](https://github.com/backbonesk/php-activerecord/commit/6f96726))
 - **Changed**: PHP 8.1 ([`1d14161`](https://github.com/backbonesk/php-activerecord/commit/1d14161),
   [`d77aa36`](https://github.com/backbonesk/php-activerecord/commit/d77aa36))
 - **Changed**: PHP 8.0 - required parameter after an optional one, `private final` methods
   ([`a15d12d`](https://github.com/backbonesk/php-activerecord/commit/a15d12d),
   [`ed2a42f`](https://github.com/backbonesk/php-activerecord/commit/ed2a42f))
 - **Changed**: PHP 7.4 ([`09cc57f`](https://github.com/backbonesk/php-activerecord/commit/09cc57f))
 - **⚠️ Breaking**: the minimum PHP version is 7.1
   ([`57d434c`](https://github.com/backbonesk/php-activerecord/commit/57d434c))

#### Upstream changes between 1.0 and the fork (2010 - 2012, selection)
 - table schema and model caching (Memcache, Memcached, APC), cache namespace
 - `SqliteAdapter`, `PgsqlAdapter` fixes, `?charset=` in the connection string
 - eager loading respects relationship options, namespaces in `has_many` `through`
 - `Model::query()`, `reestablish_connection()`, `attribute_is_dirty()`, `validate()`
 - `update_all()` / `delete_all()` with `limit` / `order` (MySQL, SQLite)
 - `to_csv` and `to_array` serialization, `Errors::to_array()`
 - `assign_attribute` for columns whose inflected name differs (capital letters)

### 1.0 (June 27, 2010)
 - `d2bed65` fixed an error with eager loading when no records exist
 - `c225942` fixed set methods on DateTime objects to properly flag attributes as dirty
 - `46a1219` fixed a memory leak when using validations
 - `c225942` fixed problem with some model functionality not working correctly after being deserialized
 - `3e26749` fixed validates_numericality_of to not ignore other options when only_integer is present and matches
 - `53ad5ec` fixed ambiguous id error when finding by pk with a join option
 - `26e40f4` fixed conditions to accept DateTime values
 - `41e52fe` changed serialization to serialize datetime fields as strings instead of the actual DateTime objects
 - `dbee94b` Model::transaction() now returns true if commit was successful otherwise false

### 1.0 RC1 (May 7, 2010)
 - support for Oracle
 - support for PostgreSQL
 - added delegators
 - added setters
 - added getters
 - added HAVING as a finder option
 - added ability to find using a hash
 - added find_or_create_by
 - added validates_uniqueness_of
 - added dynamic count_by
 - added eager loading
