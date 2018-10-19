<?php

namespace Larangular\MigrationPackage\Commands;

use Illuminate\Console\Command;
use Larangular\MigrationPackage\Contracts\CanMigrate;
use Larangular\Support\Instance;

class GenerateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larangular:migration-import
                            {class : Full Qualify namespace to class implementing CanMigrate }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate data files from CSV.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $class = $this->argument('class');
        $migratePackage = new $class;

        if(Instance::hasInterface($migratePackage, CanMigrate::class)){
            $this->makeMigration($migratePackage);
            $this->makeSeed($migratePackage);
        } else {
            $this->info("Invalid migration package {$class}");
        }
    }

    private function makeMigration(CanMigrate $migratePackage) {
        $this->info("Starting migration: {$migratePackage->databaseConnection()} - {$migratePackage->migrationsPath()}");
        $this->call('migrate:refresh', [
            '--database' => $migratePackage->databaseConnection(),
            '--path' => $migratePackage->migrationsPath()
        ]);
    }

    private function makeSeed(CanMigrate $migratePackage) {
        $this->info("Starting seed:");
        foreach($migratePackage->seeders() as $seeder) {
            $this->call('db:seed', [
                '--class' => $seeder,
                '--database' => $migratePackage->databaseConnection()
            ]);
        }
    }


}
