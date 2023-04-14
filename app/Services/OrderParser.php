<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class OrderParser
{
    public function parseRow($id, $name, $createdAt, $product_name, $locationSeller)
    {
        $order = new Order();
        $order->id = $id;
        $order->name = $name;

        $product = Product::firstWhere('name', $product_name);
        if (!$product) {
            $product = new Product();
            $product->name = $product_name;
            if (!preg_match('/^[A-Z](\d+)(,(\d+))?$/', $product_name, $m)) {
                throw new \Exception('Invalid product code');
            }

            $cents = $m[1] * 100;
            if (isset($m[3])) {
                $decimalPart = $m[3];
                // Make sure we always have 2 decimals
                $decimalPart = substr($decimalPart.'0', 0, 2);
                $cents += intval($decimalPart);
            }

            $product->price = $cents;
            $product->save();
        }

        $order->product()->associate($product);
        $carbon = Carbon::createFromFormat('d/m/Y H:i', $createdAt, 'Europe/Amsterdam');
        $order->created_at = $carbon;
        $location = explode(' / ', $locationSeller)[0];
        $order->location()->associate(Location::firstOrCreate(['name' => $location]));
        $order->seller = explode(' / ', $locationSeller)[1];
        $order->save();

        return $order;
    }
}
