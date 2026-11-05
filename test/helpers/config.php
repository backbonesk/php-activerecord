<?php
/**
 * Run from the repository root after `composer install`:
 *  - all tests: vendor/bin/phpunit
 *  - one test:  vendor/bin/phpunit test/ValidationsTest.php
 *
 * Connection strings come from PHPAR_MYSQL, PHPAR_PGSQL, PHPAR_OCI and
 * PHPAR_SQLITE; tests of an unreachable database are skipped. Optional:
 * PEAR Log (SQL logging tests), Memcache/Memcached/APC (cache tests).
 */

@include_once 'Log.php';
@include_once 'Log/file.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once 'SnakeCase_PHPUnit_Framework_TestCase.php';
require_once 'DatabaseTest.php';
require_once 'AdapterTest.php';
require_once __DIR__ . '/../../lib/ActiveRecord.php';

// whether or not to run the slow non-crucial tests
$GLOBALS['slow_tests'] = false;

// whether or not to show warnings when Log or Memcache is missing
$GLOBALS['show_warnings'] = true;


if (getenv('LOG') !== 'false')
	DatabaseTest::$log = true;

ActiveRecord\Config::initialize(function($cfg)
{
	$cfg->set_model_directory(realpath(__DIR__ . '/../models'));
	$cfg->set_connections(array(
		'mysql'  => getenv('PHPAR_MYSQL')  ?: 'mysql://root@127.0.0.1/test',
		'pgsql'  => getenv('PHPAR_PGSQL')  ?: 'pgsql://test:test@127.0.0.1/test',
		'oci'    => getenv('PHPAR_OCI')    ?: 'oci://test:test@127.0.0.1/dev',
		'sqlite' => getenv('PHPAR_SQLITE') ?: 'sqlite://test.db'));

	$cfg->set_default_connection('mysql');

	for ($i=0; $i<count($GLOBALS['argv']); ++$i)
	{
		if ($GLOBALS['argv'][$i] == '--adapter')
			$cfg->set_default_connection($GLOBALS['argv'][$i+1]);
		elseif ($GLOBALS['argv'][$i] == '--slow-tests')
			$GLOBALS['slow_tests'] = true;
	}

	if (class_exists('Log_file')) // PEAR Log installed
	{
		$logger = new Log_file(dirname(__FILE__) . '/../log/query.log','ident',array('mode' => 0664, 'timeFormat' =>  '%Y-%m-%d %H:%M:%S'));
	
		$cfg->set_logging(true);
		$cfg->set_logger($logger);
	}
	else
	{
		if ($GLOBALS['show_warnings'] && !isset($GLOBALS['show_warnings_done']))
			echo "(Logging SQL queries disabled, PEAR::Log not found.)\n";

		DatabaseTest::$log = false;
	}
	
	if ($GLOBALS['show_warnings']  && !isset($GLOBALS['show_warnings_done']))
	{ 
		if (!extension_loaded('memcache'))
			echo "(Cache Tests will be skipped, Memcache not found.)\n";
	}

	date_default_timezone_set('UTC');

	$GLOBALS['show_warnings_done'] = true;
});

error_reporting(E_ALL);
?>