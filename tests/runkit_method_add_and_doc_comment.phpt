--TEST--
runkit7_method_add() function and doc_comment
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
}
runkit7_method_add('runkit_class','runkit_method','$b', 'echo "b is $b\n";', NULL, 'new doc_comment1');
runkit7_method_add('runkit_class','runkitMethod','$b', 'echo "b is $b\n";', NULL, 'new doc_comment2');
$r1 = new ReflectionMethod('runkit_class', 'runkit_method');
echo $r1->getDocComment(), "\n";
$r2 = new ReflectionMethod('runkit_class', 'runkitMethod');
echo $r2->getDocComment(), "\n";
?>
--EXPECT--
new doc_comment1
new doc_comment2
