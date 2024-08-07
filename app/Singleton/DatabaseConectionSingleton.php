<?php
namespace App\Singleton;

use Illuminate\Support\Facades\DB;

class DatabaseConectionSingleton
{
    private static $instance;

    private function __construct(){}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = DB::connection();
        }

        return self::$instance;
    }

    public static function getInstanceId()
    {
        return spl_object_id(self::getInstance());
    }
}
