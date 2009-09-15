<?php

/**
 * Test: ArrayList adding items.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Collections
 * @subpackage UnitTests
 */

require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Collections.inc';

/*use Nette\Collections\ArrayList;*/


$list = new ArrayList(NULL, 'Person');

$jack = new Person('Jack');
$mary = new Person('Mary');
$foo = new ArrayObject();



message("Adding Jack");
$list->append($jack);

message("Adding Mary");
$list->append($mary);

try {
	message("Adding invalid item");
	$list->append($foo);

} catch (Exception $e) {
	dump( $e );
}

message("Adding Jack using []");
$list[] = $jack;

try {
	message("Adding invalid item using []");
	$list[] = $foo;

} catch (Exception $e) {
	dump( $e );
}



message('count:');
dump( $list->count() );
dump( count($list) );


dump( $list );

dump( (array) $list );



message("Get Interator:");
foreach ($list as $key => $person) {
	echo $key, ' => ', $person->sayHi();
}



message("Clearing");
$list->clear();

dump( $list );




__halt_compiler();

------EXPECT------
Adding Jack

Adding Mary

Adding invalid item

Exception InvalidArgumentException: Item must be 'Person' object.

Adding Jack using []

Adding invalid item using []

Exception InvalidArgumentException: Item must be 'Person' object.

count:

int(3)

int(3)

object(ArrayList) (3) {
	"0" => object(Person) (1) {
		"name" private => string(4) "Jack"
	}
	"1" => object(Person) (1) {
		"name" private => string(4) "Mary"
	}
	"2" => object(Person) (1) {
		"name" private => string(4) "Jack"
	}
}

array(3) {
	0 => object(Person) (1) {
		"name" private => string(4) "Jack"
	}
	1 => object(Person) (1) {
		"name" private => string(4) "Mary"
	}
	2 => object(Person) (1) {
		"name" private => string(4) "Jack"
	}
}

Get Interator:

0 => My name is Jack

1 => My name is Mary

2 => My name is Jack

Clearing

object(ArrayList) (0) {}

