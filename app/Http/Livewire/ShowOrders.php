<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Order;
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
        $query = Order::query();
        if ($this->location) {
            $query->where('location_id', $this->location);
        }

        // Carbon doesn't like year-week, see https://stackoverflow.com/a/52062781
        list($year, $week) = explode('-', $this->weeks[$this->week]);
        $weekStart = Carbon::create()
            ->setISODate($year, $week)  // TODO: verify how this works when day-year and week-year differ
            ->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        $orders = $query->get()
            ->filter(function (Order $order) use ($weekEnd, $weekStart) {
                return $order->created_at->between($weekStart, $weekEnd);
            });
        return view('livewire.show-orders', ['orders' => $orders, 'locations' => Location::all()]);
    }
}
