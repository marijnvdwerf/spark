<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class ShowOrders extends Component
{
    public function render()
    {
        return view('livewire.show-orders', ['orders' => Order::paginate(10)]);
    }
}
