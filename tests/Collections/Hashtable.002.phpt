<?php

/**
 * Test: Hashtable readonly collection.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Collections
 * @subpackage UnitTests
 */

require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Collections.inc';

/*use Nette\Collections\Hashtable;*/


$hashtable = new Hashtable(NULL, 'Person');
$hashtable['jack'] = $jack = new Person('Jack');
$hashtable['mary'] = new Person('Mary');

dump( $hashtable->isFrozen() );
$hashtable->freeze();
dump( $hashtable->isFrozen() );

try {
	message("Adding Jack using []");
	$hashtable['new'] = $jack;
} catch (Exception $e) {
	dump( $e );
}

try {
	message("Adding Jack using add");
	$hashtable->add('new', $jack);
} catch (Exception $e) {
	dump( $e );
}

try {
	message("Removing using unset");
	unset($hashtable['jack']);
} catch (Exception $e) {
	dump( $e );
}

try {
	message("Changing using []");
	$hashtable['jack'] = $jack;
} catch (Exception $e) {
	dump( $e );
}




__halt_compiler();

------EXPECT------
bool(FALSE)

bool(TRUE)

Adding Jack using []

Exception InvalidStateException: Cannot modify a frozen object 'Hashtable'.

Adding Jack using add

Exception InvalidStateException: Cannot modify a frozen object 'Hashtable'.

Removing using unset

Exception InvalidStateException: Cannot modify a frozen object 'Hashtable'.

Changing using []

Exception InvalidStateException: Cannot modify a frozen object 'Hashtable'.

