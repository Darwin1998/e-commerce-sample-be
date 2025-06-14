<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('Save'))
            ->requiresConfirmation(fn () => $this->data['status'] === OrderStatus::Cancelled->value)
            ->hidden(fn () => $this->record->status === OrderStatus::Cancelled->value)
            ->modalIcon('heroicon-o-trash')
            ->modalIconColor('warning')
            ->modalHeading(__('Confirm Cancel'))
            ->modalDescription(__('Are you sure you want to cancel this order?'))
            ->action(function () {
                $this->save();
            });
    }
}
