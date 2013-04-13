<?php

/**
 * Test: Nette\DI\Container magic properties.
 *
 * @author     David Grudl
 * @package    Nette\DI
 */

use Nette\DI\Container;



require __DIR__ . '/../bootstrap.php';



class Service
{
}

$one = new Service;
$two = new Service;


$container = new Container;
$container->one = $one;

Assert::true( isset($container->one) );
Assert::false( isset($container->undefined) );

Assert::same( $one, $container->one );

Assert::true( isset($container->one) );
Assert::false( isset($container->undefined) );
