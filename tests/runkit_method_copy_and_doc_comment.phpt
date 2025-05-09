--TEST--
runkit7_method_copy() function and doc_comment
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

class runkit_class {
	/**
	 * old doc_comment
	 */
	function runkit_method($a) {
		echo "a is $a\n";
	}
}
runkit7_method_copy('runkit_class','runkit_method1', 'runkit_class', 'runkit_method');
$r = new ReflectionMethod('runkit_class', 'runkit_method1');
echo $r->getDocComment(), "\n";
runkit7_method_redefine('runkit_class','runkit_method','', '', NULL, 'new doc_comment');
$r = new ReflectionMethod('runkit_class', 'runkit_method1');
echo $r->getDocComment(), "\n";
$r = new ReflectionMethod('runkit_class', 'runkit_method');
echo $r->getDocComment(), "\n";
echo "After redefine\n";
runkit7_method_redefine('runkit_class','runkit_method','', '', NULL, NULL);
$r = new ReflectionMethod('runkit_class', 'runkit_method');
echo $r->getDocComment(), "\n";
echo "After redefine 2\n";
runkit7_method_redefine('runkit_class','runkit_method','', '');
$r = new ReflectionMethod('runkit_class', 'runkit_method');
echo $r->getDocComment(), "\n";
echo "After redefine 3\n";
runkit7_method_redefine('runkit_class','runkit_method','', '', NULL, '');
$r = new ReflectionMethod('runkit_class', 'runkit_method');
echo $r->getDocComment(), "\n";
?>
--EXPECT--
/**
	 * old doc_comment
	 */
/**
	 * old doc_comment
	 */
new doc_comment
After redefine
new doc_comment
After redefine 2
new doc_comment
After redefine 3

