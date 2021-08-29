<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit992314273b1753584507a5f4c7d00bf1
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit992314273b1753584507a5f4c7d00bf1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit992314273b1753584507a5f4c7d00bf1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit992314273b1753584507a5f4c7d00bf1::$classMap;

        }, null, ClassLoader::class);
    }
}
