<?php

namespace Larangular\MigrationPackage\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait Schematics {

    public function create(\Closure $callback): void {
        Schema::connection($this->connection)
              ->create($this->name, function (Blueprint $table) use ($callback) {
                  $callback($table);
              });
    }

    public function alter(\Closure $callback): void {
        Schema::connection($this->connection)
              ->table($this->name, function (Blueprint $table) use ($callback) {
                  $callback($table);
              });
    }

    public function drop(): void {
        Schema::connection($this->connection)
              ->drop($this->name);
    }

    public function jsonableColumnType(): string {
        $driverName = DB::connection($this->connection)
                        ->getPdo()
                        ->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $dbVersion = DB::connection($this->connection)
                       ->getPdo()
                       ->getAttribute(\PDO::ATTR_SERVER_VERSION);
        $isOldVersion = version_compare($dbVersion, '5.7.8', 'lt');

        return $driverName === 'mysql' && $isOldVersion
            ? 'text'
            : 'json';
    }

}
