<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit034d8f091e6165f729c326d20b6acbe3
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'calisia_waitlist\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'calisia_waitlist\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit034d8f091e6165f729c326d20b6acbe3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit034d8f091e6165f729c326d20b6acbe3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
