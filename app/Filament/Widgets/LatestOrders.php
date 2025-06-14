<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_address')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('billing_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Order Status')
                    ->default('asc')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => OrderStatus::tryFrom($state)?->getLabel() ?? 'Unknown')
                    ->color(fn ($state) => OrderStatus::tryFrom($state)?->getColor())
                    ->icon(fn ($state) => OrderStatus::tryFrom($state)?->getIcon())
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Amount')
                    ->default('asc')
                    ->sortable()
                    ->money('PHP')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->button()
                    ->icon('heroicon-s-eye')
                    ->label('View')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
