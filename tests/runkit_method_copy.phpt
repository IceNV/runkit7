--TEST--
runkit7_method_copy() function
--SKIPIF--
<?php if(!extension_loaded("runkit7") || !RUNKIT7_FEATURE_MANIPULATION) print "skip"; ?>
--INI--
display_errors=on
--FILE--
<?php
if(PHP_VERSION_ID>=80400) { 
	ini_set('error_reporting', E_ALL & (~E_DEPRECATED));
} else {
	ini_set('error_reporting', E_ALL & (~E_DEPRECATED) & (~E_STRICT));
}

class runkit_one {
	public static function runkit_method($n) {
		echo "Runkit Method: $n\n";
	}

	public static function runkitMethod($n) {
		echo "Runkit Method: $n\n";
	}
}

class runkit_two {
}

runkit_one::runkit_method(1);
runkit7_method_copy('runkit_two','runkit_method','runkit_one');
runkit7_method_copy('runkit_two','runkitMethod','runkit_one');
runkit_one::runkit_method(2);
runkit_two::runkit_method(3);
runkit_one::runkitMethod(4);
runkit_two::runkitmethod(5);
runkit_two::runkitMethod(6);
runkit7_method_remove('runkit_one','runkit_method');
if (method_exists('runkit_one','runkit_method')) {
	echo "runkit_method still exists in Runkit One!\n";
}
runkit7_method_remove('runkit_one','runkitMethod');
if (method_exists('runkit_one','runkitMethod')) {
	echo "runkitMethod still exists in Runkit One!\n";
}
runkit_two::runkit_method(7);
runkit_two::runkitMethod(8);
if (class_exists('ReflectionMethod')) {
	$reflMethod = new ReflectionMethod('runkit_two', 'runkitMethod');
	$declClass = $reflMethod->getDeclaringClass();
	echo $declClass->getName(), "\n";
	echo $reflMethod->getName(), "\n";
} else {
	echo "runkit_two\n";
	echo "runkitMethod\n";
}
?>
--EXPECT--
Runkit Method: 1
Runkit Method: 2
Runkit Method: 3
Runkit Method: 4
Runkit Method: 5
Runkit Method: 6
Runkit Method: 7
Runkit Method: 8
runkit_two
runkitMethod
