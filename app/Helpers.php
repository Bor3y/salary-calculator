<?php

namespace App;

use DB;

class Helpers {

    public static function dbConfig($key){
        $record = DB::table('config')->where('key',$key)->first();
        return $record->value ?? null;
    }
}


