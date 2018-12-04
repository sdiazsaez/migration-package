<?php

namespace Larangular\MigrationPackage\Commands;

use Illuminate\Console\Command;
use Larangular\MigrationPackage\Contracts\CanMigrate;
use Larangular\Support\Instance;

class ExportTable extends Command {
    protected $signature   = 'larangular:migration-export
                            {class : Full Qualify namespace to class implementing CanMigrate }';
    protected $description = 'Generate data files from CSV.';

    public function handle() {
        $class = $this->argument('class');
        $migratePackage = new $class;

        if (Instance::hasInterface($migratePackage, CanMigrate::class)) {
            dd($migratePackage);
        } else {
            $this->info("Invalid migration package {$class}");
        }
    }

    public function getData($table, $max, $exclude = null, $orderBy = null, $direction = 'ASC')
    {
        $result = \DB::connection($this->databaseName)->table($table);

        /*
        if (!empty($exclude)) {
            $allColumns = \DB::connection($this->databaseName)->getSchemaBuilder()->getColumnListing($table);
            $result = $result->select(array_diff($allColumns, $exclude));
        }
        if($orderBy) {
            $result = $result->orderBy($orderBy, $direction);
        }
        if ($max) {
            $result = $result->limit($max);
        }
        return $result->get();
        */
    }



}
