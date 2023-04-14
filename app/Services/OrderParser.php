<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class OrderParser
{
    public function parseRow($id, $name, $createdAt, $product, $locationSeller)
    {
        $order = new Order();
        $order->id = $id;
        $order->name = $name;
        $order->product = $product;
        $carbon = Carbon::createFromFormat('d/m/Y H:i', $createdAt, 'Europe/Amsterdam');
        $order->created_at = $carbon;
        $order->location = explode(' / ', $locationSeller)[0];
        $order->seller = explode(' / ', $locationSeller)[1];
        $order->save();

        return $order;
    }
}
