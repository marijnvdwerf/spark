<?php

namespace Database\Seeders;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fh = fopen(__DIR__.'/../../orders.csv', 'r');

        // Headers
        $row = fgetcsv($fh);
        while (($row = fgetcsv($fh)) !== false) {
            $order = new Order();
            $order->id = $row[0];
            $order->name = $row[1];
            $order->created_at = Carbon::createFromFormat('d/m/Y H:i', $row[2])->setTimezone('Europe/Amsterdam');
            $order->product = $row[3];
            $order->location = explode(' / ', $row[4])[0];
            $order->seller = explode(' / ', $row[4])[1];
            $order->save();
        }
    }
}
