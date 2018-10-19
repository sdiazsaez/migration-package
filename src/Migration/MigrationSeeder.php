<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 3/21/18
 * Time: 00:09
 */

namespace Larangular\MigrationPackage\Migration;

trait MigrationSeeder {

    public function getData($filepath){
        if(file_exists($filepath)) {
            return json_decode(file_get_contents($filepath), true);
        }
    }

}