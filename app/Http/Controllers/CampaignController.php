<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;

class CampaignController extends Controller
{
    public function show()
    {
        $orders = [];

        $fh = fopen(__DIR__.'/../../../orders.csv', 'r');

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
            $orders[] = $order;
        }

        return view('campaign', ['orders' => $orders]);
    }
}
