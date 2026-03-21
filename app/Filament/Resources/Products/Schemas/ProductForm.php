<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur:true)//watches for changes
                    ->afterStateUpdated(function ($state,callable $set){
                        $set('slug',Str::slug($state));//auto generates slug
                    }),                 
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord:true)//ensured slug is unique
                    ->readOnly(),//prevents manual editing
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                 
                Select::make('category_id')        
                    ->relationship('category', 'name')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('dumatech/products'),
            ]);
    }
}