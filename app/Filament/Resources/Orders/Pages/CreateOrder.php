<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    protected function afterCreate(): void
    {
        // $this->record is the newly created Order model
        $order = $this->record;

        // Eager load the relationship to ensure the item totals are available
        $order->load('Items');

        // Calculate the total by summing the 'total_amount' from the relationship
        $grandTotal = $order->Items->sum('total_amount');

        // Update the grand_total column on the Order model
        $order->update([
            'grand_total' => $grandTotal,
        ]);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

}
