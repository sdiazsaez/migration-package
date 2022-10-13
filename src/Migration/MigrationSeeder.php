<?php

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

        if(is_null($response)) {
            d('empty response from '. $filepath);
            $response = [];
        }

        return $response;
    }

    private function migrationSeeder_getExtension(string $filepath): string {
        $pieces = explode('.', $filepath);
        return array_pop($pieces);
    }

    private function migrationSeeder_LoadCsv(string $filepath): array {
        $file = file($filepath, FILE_SKIP_EMPTY_LINES);
        $rows = array_map('str_getcsv', $file);
        $header = array_shift($rows);
        $csv = [];
        unset($file);

        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
            unset($row);
        }

        unset($header);
        unset($rows);
        return $csv;
    }

    public function migrationSeeder_LoadChunkCsv(string $filepath, ?int $size = 500, $callback) {
        $header = null;
        $this->file_get_contents_chunked($filepath, $size,
            static function ($chunk, &$handle, $iteration) use (&$header, $callback) {
                $rows = array_map('str_getcsv', $chunk);
                if ($iteration === 0) {
                    $header = array_shift($rows);
                }
                $csv = [];
                foreach ($rows as $row) {
                    $csv[] = array_combine($header, $row);
                    unset($row);
                }
                call_user_func_array($callback, [
                    $csv,
                    $iteration,
                ]);
                unset($csv);
                unset($rows);
            });
        unset($header);
    }

    private function file_get_contents_chunked($file, $chunk_size, $callback) {
        try {
            $handle = fopen($file, 'rb');
            $i = 0;

            while (($line = fgets($handle)) !== false) {
                $chunk[] = $line;
                if (count($chunk) === $chunk_size) {
                    call_user_func_array($callback, [
                        $chunk,
                        &$handle,
                        $i,
                    ]);
                    $i++;
                    $chunk = [];
                }
            }
            fclose($handle);

        } catch (Exception $e) {
            trigger_error("file_get_contents_chunked::" . $e->getMessage(), E_USER_NOTICE);
            return false;
        }

        return true;
    }

}
