<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends TableWidget
{
    protected int|string|array $columnSpan='full';
    protected static ?int $sort=2;
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query())->defaultPaginationPageOption(5)->defaultSortOptionLabel('created_at')
            ->columns([
                TextColumn::make('payment_status')->badge()->color(fn (string $state): string => match ($state) {

                    'pending' => 'warning',
                    'paid' => 'success',
                    'failed'=>'danger'
                }),
                TextColumn::make('status')
                    ->badge()->color(fn (string $state): string => match ($state) {

                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                    }),

                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('shipping_method')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->searchable(),
                ])

            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('view order')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view',['record'=>$record]))
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
