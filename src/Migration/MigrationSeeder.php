<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 3/21/18
 * Time: 00:09
 */

namespace Larangular\MigrationPackage\Migration;

trait MigrationSeeder {

    public function getData(string $filepath): array {
        $response = [];
        if (file_exists($filepath)) {
            switch ($this->migrationSeeder_getExtension($filepath)) {
                case 'csv':
                    $response = $this->migrationSeeder_LoadCsv($filepath);
                    break;
                default:
                    $response = json_decode(file_get_contents($filepath), true);
                    break;
            }
        }
        return $response;
    }


    private function migrationSeeder_getExtension(string $filepath): string {
        return array_pop(explode('.', $filepath));
    }

    private function migrationSeeder_LoadCsv(string $filepath): array {
        $rows = array_map('str_getcsv', file($filepath));
        $header = array_shift($rows);
        $csv = [];
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
        return $csv;
    }


}
