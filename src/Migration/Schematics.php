<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 3/25/18
 * Time: 05:31
 */

namespace Larangular\MigrationPackage\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait Schematics {

    public function create(\Closure $callback){

        Schema::connection($this->connection)
            ->create($this->name, function(Blueprint $table) use ($callback)
            {
                $callback($table);
            });

    }

    public function drop(){
        Schema::connection($this->connection)->drop($this->name);
    }

}