<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->label('Customer'),
            Select::make('status')
                ->options([
                    'pending'    => 'Pending',
                    'processing' => 'Processing',
                    'shipped'    => 'Shipped',
                    'delivered'  => 'Delivered',
                    'cancelled'  => 'Cancelled',
                ])
                ->required(),
            TextInput::make('total')
                ->numeric()
                ->prefix('$')
                ->required(),
            TextInput::make('address')
                ->required(),
            TextInput::make('city')
                ->required(),
            TextInput::make('phone')
                ->required(),
            Textarea::make('notes')
                ->nullable(),
        ]);
    }
}
