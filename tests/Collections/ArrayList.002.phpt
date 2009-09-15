<?php

/**
 * Test: ArrayList readonly collection.
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
$list[] = new Person('Mary');
$list[] = new Person('Larry');

dump( $list->isFrozen() );
$list->freeze();
dump( $list->isFrozen() );

try {
	message("Adding Jack using []");
	$list[] = $jack;
} catch (Exception $e) {
	dump( $e );
}

try {
	message("Adding Jack using insertAt");
	$list->insertAt(0, $jack);
} catch (Exception $e) {
	dump( $e );
}

try {
	message("Removing using unset");
	unset($list[1]);
} catch (Exception $e) {
	dump( $e );
}

try {
	message("Changing using []");
	$list[1] = $jack;
} catch (Exception $e) {
	dump( $e );
}



__halt_compiler();

------EXPECT------
bool(FALSE)

bool(TRUE)

Adding Jack using []

Exception InvalidStateException: Cannot modify a frozen object 'ArrayList'.

Adding Jack using insertAt

Exception InvalidStateException: Cannot modify a frozen object 'ArrayList'.

Removing using unset

Exception InvalidStateException: Cannot modify a frozen object 'ArrayList'.

Changing using []

Exception InvalidStateException: Cannot modify a frozen object 'ArrayList'.

