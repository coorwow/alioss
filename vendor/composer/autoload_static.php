<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit68167c9c291625d00b0b86152a0d05dd
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'OSS\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'OSS\\' => 
        array (
            0 => __DIR__ . '/..' . '/aliyuncs/oss-sdk-php/src/OSS',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit68167c9c291625d00b0b86152a0d05dd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit68167c9c291625d00b0b86152a0d05dd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
