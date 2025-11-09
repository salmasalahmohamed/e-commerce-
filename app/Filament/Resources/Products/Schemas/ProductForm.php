<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('category_id')
                    ->required()
                    ->searchable()
                    ->preload()
                ->relationship('category','name'),
                Select::make('brand_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('brand','name'),

                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state,string $operation) =>   $operation=='create'?$set('slug', Str::slug($state)):null),

                TextInput::make('slug')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->directory('products')
                    ->multiple()
                    ->dehydrated(true)
                    ->nullable(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                Toggle::make('is_stock')
                    ->required(),
                Toggle::make('is_sale')
                    ->required(),
            ]);
    }
}
