<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Filament\Resources\Components\Tab;


class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array{
        return [
            // Add your header widgets here.
            OrderStats::class

        ];
    }

    public function getTabs(): array {
        return [
            null => Tab::make('All'),
            'new' => Tab::make()->query(fn ($query)=> $query->where('order_status','new')),
        'processing' => Tab::make()->query(fn ($query)=> $query->where('order_status','processing')),
        'shipped' =>Tab::make()->query(fn ($query)=> $query->where('order_status','shipped')),
        'delivered' => Tab::make()->query(fn ($query)=> $query->where('order_status','delivered')),
        'cancelled' => Tab::make()->query(fn ($query)=> $query->where('order_status','cancelled')),
        'refund' => Tab::make()->query(fn ($query)=> $query->where('order_status','refund'))

        ];
    }
}
