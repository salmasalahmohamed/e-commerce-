<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;

class OrderForm
{
    public static function booted(): void
    {
        DB::listen(function ($query) {
            Log::info('SQL QUERY', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
            ]);
        });
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                section::make([

                    select::make('user_id')
                    ->label('customer')
                    ->relationship('user','name')
                    ->required()
                    ->preload()
                    ->searchable(),



                    Select::make('payment_method')
                        ->options([
                            'stripe' => 'strip',
                            'cod' => 'cash on Delivery',

                        ])->required(),
                    Select::make('payment_status')
                        ->options([
                            'pending' => 'pending',
                            'paid' => 'paid',
                            'failed'=>'failed'

                        ])->required()->
                        default('pending'),

                    ToggleButtons::make('status')->inline()
                        ->colors([
                            'new' => 'info',
                            'processing' => 'warning',
                            'shipped' => 'success',
                            'delivered' => 'success',
                            'canceled' => 'danger',
                            ])->icons([

                                'new' => Heroicon::Sparkles,
                    'processing' => Heroicon::OutlinedArrowPathRoundedSquare,
                            'shipped' => Heroicon::OutlinedTruck,
                            'delivered' => Heroicon::CheckBadge,
                            'canceled' => Heroicon::OutlinedXCircle,

                            ])
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'canceled' => 'Canceled',
                        ])
                        ->default('new')
                        ->required(),
                    Select::make('currency')
                        ->options([
                            'eur' => 'EUR',
                            'usd' => 'USD',
                            'egp'=>'EGP'

                        ])->required()->default('egp'),

                    Select::make('shipping_method')
                        ->options([
                            'aramex' => 'Aramex - Parcel',
                            'airParcel' => 'AirParcel XP.',

                        ])->required(),
                    Textarea::make('notes')
                        ->columnSpanFull(),

                ])->columnSpanFull(),
                section::make('order items')->schema([
                    Repeater::make('Items')->relationship()->live()
                    ->schema([
                        select::make('product_id')->relationship('product','name')
                        ->searchable()
                            ->preload()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->reactive()
                        ->afterStateUpdated(fn($state,  Set $set )=>$set('unit_amount',Product::find($state)->price??0))
                            ->afterStateUpdated(fn($state,  Set $set )=>$set('total_amount',Product::find($state)->price??0)

                            ),

                        TextInput::make('quantity')->numeric()
                        ->required()
                        ->default(1)
                        ->minValue(1)
                            ->reactive()

                            ->afterStateUpdated(fn($state,  Set $set ,Get $get )=>$set('total_amount',$get('unit_amount')*$state??0))
                        ,


                         TextInput::make('unit_amount')->numeric()
                             ->required()
                             ->default(1)
                             ->disabled()->dehydrated(),
                         TextInput::make('total_amount')->numeric()
                             ->required()
                        ->dehydrated(),






                    ]),


                 Placeholder::make('total')
                        ->label('Total')
                        ->reactive()

                        ->content(fn ( Set $set,Get $get) => function () use ($get,$set) {
                            $total = 0;
                            $items = $get('Items') ?? [];

                            foreach ($items as $item) {
                                $total += (float) ($item['total_amount'] ?? 0);
                            }
                            return number_format($total, 2) . ' EGP';
                        }),



                ])->columnSpanFull()




            ]);
    }
}
