<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;

class ShowOrders extends Component
{
    public $location;

    // TODO: refactor this to share less data with the client.
    public $weeks;

    public $week;

    public function mount()
    {
        $this->weeks = $this->getWeeks();
        $this->week = count($this->weeks) - 1; // Select most recent week
    }

    private function getWeeks()
    {
        // TODO: refactor and look into making database agnostic
        $days = Order::select('created_at')
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->map(fn(Order $order) => $order->created_at)
            ->sort();

        $weeks = [];

        /** @var Carbon $lastDay */
        $lastDay = $days->last();

        /** @var Carbon $startOfWeek */
        $startOfWeek = $days->first()->copy()->startOfWeek();
        while (true) {
            if ($startOfWeek > $lastDay) {
                break;
            }

            $weeks[] = $startOfWeek->format('o-W');
            $startOfWeek = $startOfWeek->addWeek();
        }

        return $weeks;
    }

    public function render()
    {
        // Carbon doesn't like year-week, see https://stackoverflow.com/a/52062781
        list($year, $week) = explode('-', $this->weeks[$this->week]);
        $weekStart = Carbon::create()
            ->setISODate($year, $week)  // TODO: verify how this works when day-year and week-year differ
            ->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        $orders = Order::get()
            ->filter(function (Order $order) use ($weekEnd, $weekStart) {
                return $order->created_at->between($weekStart, $weekEnd);
            });

        $locations = Location::all();
        $locationOrders = $orders->groupBy('location_id');
        $stats = [];
        foreach ($locations as $location) {
            $count = null;
            if (isset($locationOrders[$location->id])) {
                $count = count($locationOrders[$location->id]);
            }
            $stats[] = [
                'description' => $location->name,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'value' => $count
            ];
        }

        // TODO: only get products for this campaign
        $products = Product::all()->sortBy('price');


        $locationOrdersByProduct = [];
        $ordersByProduct = $orders->groupBy('product_id');
        if ($this->location && isset($locationOrders[$this->location])) {
            $locationOrdersByProduct = $locationOrders[$this->location]->groupBy('product_id');
        }


        $productStats = [];
        foreach ($products as $product) {
            $productStats[] = [
                'product' => $product->name,
                'value' => count($locationOrdersByProduct[$product->id] ?? []),
                'total' => count($ordersByProduct[$product->id] ?? []),
            ];
        }

        $productStatsMax = collect($productStats)->pluck('total')->max();
        $productStatsLocationMax = collect($productStats)->pluck('value')->max();

        // Fix for every value being highlighted
        $productStatsMax = max($productStatsMax, 1);
        $productStatsLocationMax = max($productStatsLocationMax, 1);


        $this->emit('updateChart', $stats);
        return view('livewire.show-orders', [
            'stats' => $stats,
            'productStats' => $productStats,
            'productStatsMax' => $productStatsMax,
            'productStatsLocationMax' => $productStatsLocationMax,
            'locations' => Location::all()
        ]);
    }
}
