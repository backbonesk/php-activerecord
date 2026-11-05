# CHANGELOG

## Inštrukcie

Fork [kla/php-activerecord](https://github.com/kla/php-activerecord) udržiavaný firmou BACKBONE ako
`backbone/php-activerecord`. Záznamy od verzie 1.1.0 sú po slovensky, staršie sú pôvodné z upstreamu.

| Vetva         | Účel                                                                    |
|---------------|-------------------------------------------------------------------------|
| `development` | vývoj                                                                   |
| `master`      | vydania - zapisuje sa iba cez PR z `development`, tagy sa robia z neho  |
| `legacy-php7` | pôvodný `master` pred podporou PHP 8 (bez vydaní)                       |

Každá verzia je git tag na `master` (semantické verziovanie). V `composer.json` aplikácie používajte tag
(napr. `"backbone/php-activerecord": "^1.1"`), nie vetvu.

## Releases

### 1.1.1 (05.11.2026)
 - **Fixed**: `validates_uniqueness_of` obaľuje názvy stĺpcov aj primárneho kľúča úvodzovkami, rovnako ako hash
   podmienky v `SQLBuilder`. Stĺpec s rezervovaným slovom (`key`, `order`) predtým na MySQL/MariaDB končil SQL
   chybou 1064 už pri uložení záznamu. Prevzaté z upstream `99904dc`, ktoré bolo iba vo vetve `legacy-php7`;
   pribudol test s tabuľkou `valuestore` pre mysql/pgsql/sqlite/oci
   ([`db6134b`](https://github.com/backbonesk/php-activerecord/commit/db6134b))

### 1.1.0 (05.11.2026)
Prvá verzia s podporou PHP 8.x (8.1 až 8.5), minimálna verzia PHP je 7.1. Obsahuje všetky úpravy forku od 1.0
(vetva `php82`, predtým `dev`).

#### Odchýlky forku od upstreamu (od 2013)
 - **⚠️ Breaking**: `Model::find()` (`first`, `last`, `all`) aj `find_by_*` / `find_all_by_*` pri prázdnom výsledku
   vyhadzujú `RecordNotFound` (upstream vracia `null` / prázdne pole); `find_or_create_by_*` výnimku zachytí
   a záznam vytvorí ([`8bb4966`](https://github.com/backbonesk/php-activerecord/commit/8bb4966))
 - **Changed**: lazy načítané vzťahy (`belongs_to`, `has_one`, `has_many`) pri chýbajúcom zázname vracajú `null` /
   prázdne pole namiesto výnimky ([`8bb4966`](https://github.com/backbonesk/php-activerecord/commit/8bb4966),
   [`f693bcb`](https://github.com/backbonesk/php-activerecord/commit/f693bcb))
 - **Changed**: štruktúra súborov podľa PSR-0 (`lib/ActiveRecord`), composer autoload
   ([`c5acfad`](https://github.com/backbonesk/php-activerecord/commit/c5acfad))
 - **Changed**: balík premenovaný na `backbone/php-activerecord`
   ([`132b0d6`](https://github.com/backbonesk/php-activerecord/commit/132b0d6))
 - **Added**: profilovanie dotazov - log obsahuje aj trvanie dotazu
   ([`13c7a24`](https://github.com/backbonesk/php-activerecord/commit/13c7a24))
 - **Added**: vlastná trieda validácií cez `Config::set_validator()`
   ([`4796087`](https://github.com/backbonesk/php-activerecord/commit/4796087))
 - **Added**: `isset($model->x)` skúša aj magické gettery
   ([`e4c3f89`](https://github.com/backbonesk/php-activerecord/commit/e4c3f89))
 - **Fixed**: chybná trieda vo vzťahu (Reflection) skončí čitateľnou chybou
   ([`75eb052`](https://github.com/backbonesk/php-activerecord/commit/75eb052))

#### Opravy
 - **Fixed**: `datetime_to_string()` bez časovej zóny (`Y-m-d H:i:s`) - MariaDB/MySQL v strict `sql_mode` hodnotu
   s `T` odmietali (chyba 1292) ([`54c3939`](https://github.com/backbonesk/php-activerecord/commit/54c3939))
 - **Fixed**: načítanie vzťahov `has_many` s `through`
   ([`3a21b5c`](https://github.com/backbonesk/php-activerecord/commit/3a21b5c))
 - **Fixed**: `PgsqlAdapter` nepoužíva `pg_attrdef.adsrc` (neexistuje od PostgreSQL 12)
   ([`9985b94`](https://github.com/backbonesk/php-activerecord/commit/9985b94))
 - **Fixed**: `find_by_pk()` so skalárnou hodnotou (PHP 8: `count()` na skalár)
   ([`6b534d5`](https://github.com/backbonesk/php-activerecord/commit/6b534d5))
 - **Fixed**: atribút je dirty iba pri zmene hodnoty aj typu (`!==`)
   ([`78eb892`](https://github.com/backbonesk/php-activerecord/commit/78eb892))
 - **Fixed**: atribút sa označí ako dirty iba vtedy, keď sa hodnota naozaj zmenila
   ([`00325f2`](https://github.com/backbonesk/php-activerecord/commit/00325f2),
   [`f5d94d5`](https://github.com/backbonesk/php-activerecord/commit/f5d94d5))
 - **Fixed**: `DateTime` - kompatibilita s PHP 7.1
   ([`401f4d3`](https://github.com/backbonesk/php-activerecord/commit/401f4d3))

#### Kompatibilita s PHP
 - **Changed**: PHP 8.5 - vlastnosti v reťazcoch s `{}`, `static::` callable, nedeklarovaný `$klass`, `fputcsv`
   escape, `strlen(null)` v `Utils`/`Expressions`, `is_hash`
   ([`cced8d0`](https://github.com/backbonesk/php-activerecord/commit/cced8d0))
 - **Changed**: PHP 8.4 - explicitne nullable typy, `AllowDynamicProperties`, návratový typ `Errors::getIterator`
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
 - **Changed**: PHP 8.2 ([`268e441`](https://github.com/backbonesk/php-activerecord/commit/268e441) až
   [`6f96726`](https://github.com/backbonesk/php-activerecord/commit/6f96726))
 - **Changed**: PHP 8.1 ([`1d14161`](https://github.com/backbonesk/php-activerecord/commit/1d14161),
   [`d77aa36`](https://github.com/backbonesk/php-activerecord/commit/d77aa36))
 - **Changed**: PHP 8.0 - povinný parameter za voliteľným, `private final` metódy
   ([`a15d12d`](https://github.com/backbonesk/php-activerecord/commit/a15d12d),
   [`ed2a42f`](https://github.com/backbonesk/php-activerecord/commit/ed2a42f))
 - **Changed**: PHP 7.4 ([`09cc57f`](https://github.com/backbonesk/php-activerecord/commit/09cc57f))
 - **⚠️ Breaking**: minimálna verzia PHP je 7.1
   ([`57d434c`](https://github.com/backbonesk/php-activerecord/commit/57d434c))

#### Upstream zmeny medzi 1.0 a forkom (2010 - 2012, výber)
 - cache schémy tabuliek a modelov (Memcache, Memcached, APC), namespace pre cache
 - `SqliteAdapter`, opravy `PgsqlAdapter`, `?charset=` v connection stringu
 - eager loading - rešpektuje voľby vzťahov, namespace v `has_many` `through`
 - `Model::query()`, `reestablish_connection()`, `attribute_is_dirty()`, `validate()`
 - `update_all()` / `delete_all()` s `limit` / `order` (MySQL, SQLite)
 - serializácia `to_csv` a `to_array`, `Errors::to_array()`
 - `assign_attribute` pre stĺpce s iným inflected názvom (veľké písmená)

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
