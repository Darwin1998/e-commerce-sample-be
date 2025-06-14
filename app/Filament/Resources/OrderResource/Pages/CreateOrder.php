<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use App\Actions\Orders\CreateOrderAction;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateOrderAction::class)->execute($data);
    }

    protected function afterCreate(): void
    {
        /** @var Order $order */
        $order = $this->record;

        /** @var User $user */
        $user = auth()->user();

        Notification::make()
            ->title('New order')
            ->icon('heroicon-o-shopping-bag')
            ->body("**{$order->customer?->name} ordered {$order->orderProducts()->count()} products.**")
            ->actions([
                Action::make('View')
                    ->url(OrderResource::getUrl('view', ['record' => $order])),
            ])
            ->sendToDatabase($user);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Order Created Successfully')
            ->success()
            ->send();
    }
}
