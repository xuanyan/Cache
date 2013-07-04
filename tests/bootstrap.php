<?php

require dirname(__DIR__).'/src/Cache.php';

function MyDelete($dir)
{
    if (!file_exists($dir)) {
        return false;
    }

    if (is_file($dir)) {
        return unlink($dir);
    }

    $path = new DirectoryIterator($dir);
    foreach ($path as $val) {
        if (!$val->isDot()) {
            MyDelete($val->getPathname());
        }
    }

    return rmdir($dir);
}