<?php

/**
 * Singleton trait
 *
 * @author Geert Weggemans - geert@man-kind.nl
 * @date jan 8 2020
 */

namespace Wiki\tools\traits;

trait tSingleton
{
    // for inheritance, this needs to be an array! ;-)    
    protected static $_instance = [];

    public static function getInstance()
    {
        $class = get_called_class();
        //\ManKind\tools\dev\Logger::_echo('getInstance Called for class ['.$class.']');
        if (isset(static::$_instance[$class]) === false) {
            // See late static binding            
            static::$_instance[$class] = new $class;
        }
        //\ManKind\tools\dev\Logger::_echo('Instance is class ['.get_class(static::$_instance[$class]).']');
        return static::$_instance[$class];
    }

    //=============================================================================
    public function __clone()
    {
        throw new \Error('Cloning ' . get_called_class() . ' is not allowed.');
    }

    //=============================================================================
    public function __wakeup()
    {
        throw new \Error('Unserializing ' . get_called_class() . ' is not allowed.');
    }
}
