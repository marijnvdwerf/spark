<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Location;
use App\Models\Order;
use App\Services\OrderParser;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderParserTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_order(): void
    {
        $parser = $this->app->make(OrderParser::class);
        $this->assertDatabaseCount('orders', 0);

        $order = $parser->parseRow('82479', 'S. Schippers', '08/12/2020 18:43', 'D12,5', 'Nijmegen / Esther Oostland');

        $this->assertDatabaseCount('orders', 1);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertSame(82479, $order->id);
        $this->assertSame('S. Schippers', $order->name);
        $this->assertEquals(Carbon::create(2020, 12, 8, 18, 43, 00, 'Europe/Amsterdam'), $order->created_at);
        $this->assertSame('D12,5', $order->product);
        $this->assertSame('Nijmegen', $order->location->name);
        $this->assertSame('Esther Oostland', $order->seller);
    }

    public function test_uses_existing_location(): void
    {
        $parser = $this->app->make(OrderParser::class);

        $location = new Location();
        $location->name = 'Nijmegen';
        $location->save();

        $this->assertDatabaseCount('locations', 1);
        $this->assertDatabaseCount('orders', 0);

        $order = $parser->parseRow('82479', 'S. Schippers', '08/12/2020 18:43', 'D12,5', 'Nijmegen / Esther Oostland');

        $this->assertDatabaseCount('locations', 1);
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame($location->id, $order->location->id);
    }
}
