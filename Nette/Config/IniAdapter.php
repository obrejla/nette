<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette\Config;

use Nette;



/**
 * Reading and writing INI files.
 *
 * @author     David Grudl
 */
final class IniAdapter implements IAdapter
{

	/** @var string  key nesting separator (key1> key2> key3) */
	public static $keySeparator = '.';

	/** @var string  section inheriting separator (section < parent) */
	public static $sectionSeparator = ' < ';

	/** @var string  raw section marker */
	public static $rawSection = '!';



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new Nette\StaticClassException;
	}



	/**
	 * Reads configuration from INI file.
	 * @param  string  file name
	 * @return array
	 * @throws Nette\InvalidStateException
	 */
	public static function load($file)
	{
		if (!is_file($file) || !is_readable($file)) {
			throw new Nette\FileNotFoundException("File '$file' is missing or is not readable.");
		}

		Nette\Diagnostics\Debugger::tryError();
		$ini = parse_ini_file($file, TRUE);
		if (Nette\Diagnostics\Debugger::catchError($e)) {
			throw new Nette\InvalidStateException('parse_ini_file(): ' . $e->getMessage(), 0, $e);
		}

		$separator = trim(self::$sectionSeparator);
		$data = array();
		foreach ($ini as $secName => $secData) {
			// is section?
			if (is_array($secData)) {
				if (substr($secName, -1) === self::$rawSection) {
					$secName = substr($secName, 0, -1);

				} elseif (self::$keySeparator) {
					// process key separators (key1> key2> key3)
					$tmp = array();
					foreach ($secData as $key => $val) {
						$cursor = & $tmp;
						foreach (explode(self::$keySeparator, $key) as $part) {
							if (!isset($cursor[$part]) || is_array($cursor[$part])) {
								$cursor = & $cursor[$part];
							} else {
								throw new Nette\InvalidStateException("Invalid key '$key' in section [$secName] in '$file'.");
							}
						}
						$cursor = $val;
					}
					$secData = $tmp;
				}

				// process extends sections like [staging < production] (with special support for separator ':')
				$parts = $separator ? explode($separator, strtr($secName, ':', $separator)) : array($secName);
				if (count($parts) > 1) {
					$parent = trim($parts[1]);
					$cursor = & $data;
					foreach (self::$keySeparator ? explode(self::$keySeparator, $parent) : array($parent) as $part) {
						if (isset($cursor[$part]) && is_array($cursor[$part])) {
							$cursor = & $cursor[$part];
						} else {
							throw new Nette\InvalidStateException("Missing parent section [$parent] in '$file'.");
						}
					}
					$secData = Nette\Utils\Arrays::mergeTree($secData, $cursor);
				}

				$secName = trim($parts[0]);
				if ($secName === '') {
					throw new Nette\InvalidStateException("Invalid empty section name in '$file'.");
				}
			}

			if (self::$keySeparator) {
				$cursor = & $data;
				foreach (explode(self::$keySeparator, $secName) as $part) {
					if (!isset($cursor[$part]) || is_array($cursor[$part])) {
						$cursor = & $cursor[$part];
					} else {
						throw new Nette\InvalidStateException("Invalid section [$secName] in '$file'.");
					}
				}
			} else {
				$cursor = & $data[$secName];
			}

			if (is_array($secData) && is_array($cursor)) {
				$secData = Nette\Utils\Arrays::mergeTree($secData, $cursor);
			}

			$cursor = $secData;
		}

		return $data;
	}



	/**
	 * Write INI file.
	 * @param  Config to save
	 * @param  string  file
	 * @return void
	 */
	public static function save($config, $file)
	{
		$output = array();
		$output[] = '; generated by Nette';// at ' . @strftime('%c');
		$output[] = '';

		foreach ($config as $secName => $secData) {
			if (!(is_array($secData) || $secData instanceof \Traversable)) {
				throw new Nette\InvalidStateException("Invalid section '$secName'.");
			}

			$output[] = "[$secName]";
			self::build($secData, $output, '');
			$output[] = '';
		}

		if (!file_put_contents($file, implode(PHP_EOL, $output))) {
			throw new Nette\IOException("Cannot write file '$file'.");
		}
	}



	/**
	 * Recursive builds INI list.
	 * @param  array|\Traversable
	 * @param  array
	 * @param  string
	 * @return void
	 */
	private static function build($input, & $output, $prefix)
	{
		foreach ($input as $key => $val) {
			if (is_array($val) || $val instanceof \Traversable) {
				self::build($val, $output, $prefix . $key . self::$keySeparator);

			} elseif (is_bool($val)) {
				$output[] = "$prefix$key = " . ($val ? 'true' : 'false');

			} elseif (is_numeric($val)) {
				$output[] = "$prefix$key = $val";

			} elseif (is_string($val)) {
				$output[] = "$prefix$key = \"$val\"";

			} else {
				throw new Nette\InvalidArgumentException("The '$prefix$key' item must be scalar or array, " . gettype($val) ." given.");
			}
		}
	}

}
