<?php

namespace Merlin;

spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . end($parts) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

function assertHandler($file, $line, $code, $desc = null) {
    throw new MerlinException($desc);
}
assert_options(ASSERT_CALLBACK, 'assertHandler');
