<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status','new')->count()),
            Stat::make('Order Processing', Order::query()->where('status','processing')->count()),
            Stat::make('Order Shipping', Order::query()->where('status','shipped')->count()),
            Stat::make('Average Price',  number_format(Order::query()->avg('grand_total'),2)),

        ];
    }
}
