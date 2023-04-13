<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;

class CampaignController extends Controller
{
    public function show()
    {
        return view('campaign', ['orders' => Order::all()]);
    }
}
