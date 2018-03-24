<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit90c434baa7b8d3328a1fe1dd2854a6d5
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'League\\Plates\\' => 14,
        ),
        'D' => 
        array (
            'DEWordpressPlugin\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'League\\Plates\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/plates/src',
        ),
        'DEWordpressPlugin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/code',
        ),
    );

    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 
            array (
                0 => __DIR__ . '/..' . '/composer/installers/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit90c434baa7b8d3328a1fe1dd2854a6d5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit90c434baa7b8d3328a1fe1dd2854a6d5::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit90c434baa7b8d3328a1fe1dd2854a6d5::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
