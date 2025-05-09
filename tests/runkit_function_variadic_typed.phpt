--TEST--
runkit7_function_redefine() function, etc. can redefine variadic functions with return types.
--SKIPIF--
<?php if(!extension_loaded("runkit7") || !RUNKIT7_FEATURE_MANIPULATION) print "skip"; ?>
--INI--
display_errors=on
--FILE--
<?php
function create_function_mock($originalName, $temporaryName) {
    if (!runkit7_function_copy($originalName, $temporaryName))
        throw new RuntimeException($originalName . ' runkit_method_copy create_function_mock');
    if (!runkit7_function_remove($originalName))
        throw new RuntimeException($originalName . ' runkit_function_remove create_function_mock');
    if (!runkit7_function_add($originalName, '', 'printf("In mock: %s\n", serialize(func_get_args()));return null;'))
        throw new RuntimeException($originalName . ' runkit_function_add create_function_mock');
}

function remove_function_mock($originalName, $temporaryName) {
    if (!runkit7_function_remove($originalName))
        throw new RuntimeException($originalName . ' runkit_function_remove1 remove_function_mock');
    if (!runkit7_function_rename($temporaryName, $originalName))
        throw new RuntimeException($originalName . ' runkit_function_rename remove_function_mock');
}

class FooImpl {
    public function methodName($arg) {
        return 33 + $arg;
    }
}

function bar($method, ...$args) : string {
    global $impl;
    if ($impl === null) {
        throw new RuntimeException('No $impl');
    }

    $id = getter();
    return $id . call_user_func_array(array($impl, $method), $args);
}

function getter() {
    return 'VALUE';
}

function main() {
    if(PHP_VERSION_ID>=80400) {
		ini_set('error_reporting', E_ALL);
	} else {
		ini_set('error_reporting', E_ALL | E_STRICT);
	}
    global $impl;
    $impl = new FooImpl();
    printf("Before mock: %s\n", var_export(bar('methodName', 0), true));
    create_function_mock('bar', 'bar0000001123');
    printf("After mock: %s\n", var_export(bar('methodName', 0), true));
    remove_function_mock('bar', 'bar0000001123');
    printf("After unmock: %s\n", var_export(bar('methodName', 100), true));
    create_function_mock('bar', 'bar0000001123');
    printf("After mock: %s\n", var_export(bar('methodName', 0), true));
    remove_function_mock('bar', 'bar0000001123');
    printf("After unmock: %s\n", var_export(bar('methodName', 100), true));
}
main();
?>
--EXPECT--
Before mock: 'VALUE33'
In mock: a:2:{i:0;s:10:"methodName";i:1;i:0;}
After mock: NULL
After unmock: 'VALUE133'
In mock: a:2:{i:0;s:10:"methodName";i:1;i:0;}
After mock: NULL
After unmock: 'VALUE133'
