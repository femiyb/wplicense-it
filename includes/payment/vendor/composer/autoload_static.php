<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit69c4ae45876dbbed444b6a40a59d5e55
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit69c4ae45876dbbed444b6a40a59d5e55::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit69c4ae45876dbbed444b6a40a59d5e55::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit69c4ae45876dbbed444b6a40a59d5e55::$classMap;

        }, null, ClassLoader::class);
    }
}
