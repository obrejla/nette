<?php

/**
 * Test: Nette\DI\Configurator: services by Class.
 *
 * @author     David Grudl
 * @package    Nette\DI
 */

use Nette\DI\Configurator;



require __DIR__ . '/../bootstrap.php';



class Lorem
{
	function __construct(Ipsum $arg)
	{
	}
}

class Ipsum
{
	static function foo()
	{
	}
}


$configurator = new Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$container = $configurator->addConfig('files/config.factory.byClass.neon')
	->createContainer();

Assert::true( $container->one instanceof Lorem );
Assert::true( $container->two instanceof Ipsum );
Assert::true( $container->three instanceof Lorem );
Assert::same( $container->one, $container->three );
Assert::true( $container->four instanceof Lorem );
Assert::same( $container->one, $container->four );
