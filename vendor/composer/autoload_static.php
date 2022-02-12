<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2c329285a6014d7d943c980ed35a88f1
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SheetDB\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SheetDB\\' => 
        array (
            0 => __DIR__ . '/..' . '/sheetdb/sheetdb-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2c329285a6014d7d943c980ed35a88f1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2c329285a6014d7d943c980ed35a88f1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2c329285a6014d7d943c980ed35a88f1::$classMap;

        }, null, ClassLoader::class);
    }
}
