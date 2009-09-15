<?php

/**
 * Test: Set modifing iterator.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Collections
 * @subpackage UnitTests
 */

require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Collections.inc';

/*use Nette\Collections\Set;*/


$set = new Set(NULL, 'Person');
$set->append(new Person('Jack'));
$set->append(new Person('Mary'));
$set->append(new Person('Larry'));

foreach ($set as & $person) {
	$person = 10; 
}

dump( $set );



__halt_compiler();

------EXPECT------
object(Set) (3) {
	"%h%" => object(Person) (1) {
		"name" private => string(4) "Jack"
	}
	"%h%" => object(Person) (1) {
		"name" private => string(4) "Mary"
	}
	"%h%" => object(Person) (1) {
		"name" private => string(5) "Larry"
	}
}

