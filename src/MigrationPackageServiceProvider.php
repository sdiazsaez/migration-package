<?php

namespace Larangular\MigrationPackage;

use Illuminate\Support\ServiceProvider;
use Larangular\MigrationPackage\Commands\ExportTable;
use Larangular\MigrationPackage\Commands\GenerateData;

class MigrationPackageServiceProvider extends ServiceProvider {

    protected $commands = [
        GenerateData::class,
        ExportTable::class
    ];

    public function boot() {
    }

    public function register() {
        $this->commands($this->commands);
    }
}
