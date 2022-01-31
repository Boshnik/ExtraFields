<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb5a9129e8b50d0c771b9c81e268592fa
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Boshnik\\ExtraFields\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Boshnik\\ExtraFields\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb5a9129e8b50d0c771b9c81e268592fa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb5a9129e8b50d0c771b9c81e268592fa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb5a9129e8b50d0c771b9c81e268592fa::$classMap;

        }, null, ClassLoader::class);
    }
}
