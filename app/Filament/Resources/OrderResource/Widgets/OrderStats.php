<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Filament\Action;        


class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('New Order', Order::query()->where('order_status', 'new')->count()),

            Stat::make('Total Orders', Order::count())
            ->icon('heroicon-o-shopping-cart'),
        
        // Stat::make('Total Revenue', 'INR ' . Order::sum('total_amount'))
        //     ->icon('heroicon-o-currency-rupee'),

        Stat::make('Processing Order', Order::where('order_status', 'processing')->count()),

        Stat::make('Delivered Order', Order::where('order_status', 'delivered')->count()),

        Stat::make('Cancelled Order', Order::where('order_status', 'Cancelled')->count()),
        
        Stat::make('Refund Order', Order::where('order_status', 'refund')->count()),

        Stat::make('Average Order Value', 'INR ' . number_format(Order::avg('total_amount'), 2))
                ->icon('heroicon-o-currency-rupee'),

        Stat::make('Pending Orders', Order::where('order_status', 'new')->count())
            ->icon('heroicon-o-clock')
        ];
    }
    
}
