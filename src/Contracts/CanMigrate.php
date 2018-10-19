<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 3/20/18
 * Time: 23:34
 */

namespace Larangular\MigrationPackage\Contracts;

interface CanMigrate {

    public function databaseConnection();

    public function migrationsPath();

    public function seeders();
}