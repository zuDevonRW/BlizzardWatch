<?php

trait Singleton {
    protected static $instance = null;

    protected function __construct() {
        // NA
    }

    protected function __clone() {
        // NA
    }

    protected function __wakeup() {
        // NA
    }

    public static function get_instance() {
        if( !isset(self::$instance) ) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}
