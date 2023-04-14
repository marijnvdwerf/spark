<?php

namespace Database\Seeders;

use App\Services\OrderParser;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parser = app()->get(OrderParser::class);
        $fh = fopen(__DIR__.'/../../orders.csv', 'r');

        // Headers
        $row = fgetcsv($fh);
        while (($row = fgetcsv($fh)) !== false) {
            $parser->parseRow(...$row);
        }
    }
}
