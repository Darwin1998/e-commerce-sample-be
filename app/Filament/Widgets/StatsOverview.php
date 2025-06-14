<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $order = Order::all();

        return [
            Stat::make('Total Sales', Number::currency($order->sum('total_price'), 'PHP'))
                ->description('Total over all sales.')
                ->chart($order->pluck('total_price')->toArray())
                ->color('success'),
            Stat::make('Total Customers', Customer::count())
                ->color('danger'),
            Stat::make('Total Users', User::count())
                ->color('success'),
        ];
    }
}
