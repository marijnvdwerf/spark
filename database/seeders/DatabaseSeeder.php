<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Location;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Location::create(['name' => 'Rotterdam', 'latitude' => 51.9244201, 'longitude' => 4.4777325]);
        Location::create(['name' => 'HQ', 'latitude' => 52.377956, 'longitude' => 4.897070]);
        Location::create(['name' => 'Nijmegen', 'latitude' => 51.8425, 'longitude' => 5.85278]);
        Location::create(['name' => 'Utrecht', 'latitude' => 52.092876, 'longitude' => 5.104480]);
        Location::create(['name' => 'Eindhoven', 'latitude' => 51.4381, 'longitude' => 5.4752]);
        Location::create(['name' => 'Groningen', 'latitude' => 53.21917, 'longitude' => 6.56667]);
        $this->call(OrderSeeder::class);
    }
}
