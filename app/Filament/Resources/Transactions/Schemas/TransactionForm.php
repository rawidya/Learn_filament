<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use App\Models\Item;
use Filament\Schemas\Components\Section;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('user_id')
                //     ->required()
                //     ->numeric(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Hidden::make('date')
                    ->default(now())
                    ->required(),
                Section::make('Payment')
                    ->schema([
                        TextInput::make('total')->prefix('Rp. ')->disabled(),
                        TextInput::make('pay_total')->prefix('Rp. ')->numeric(),
                        TextInput::make('change')->prefix('Rp. ')->numeric()->disabled(),
                    ]),

                Section::make('items_list')
                    ->schema([
                        Repeater::make('details')->relationship()->schema([
                            Select::make('item_id')->label('Item')
                                ->options(Item::all()->pluck('name', 'id'))
                                ->required()
                                ->reactive(),
                            TextInput::make('qty')
                                ->required()
                                ->numeric()
                                ->default(1),
                       
                            
                        ]),
                    ]),
                    Section::make('Cart')
            ->schema([
        Repeater::make('item_list') 
            ->schema([
                Select::make('item_id')
                    ->label('Item')
                    ->options(Item::all()->pluck('name', 'id'))
                    ->required()
                    ->reactive(),
                TextInput::make('qty')
                    ->numeric()
                    ->default(1),
                TextInput::make('Subtotal')
                    ->prefix('Rp. ')
                    ->numeric()
                    ->disabled(),
            ])
            ->columns(3)
            ->addActionLabel('Add Item'), 

        TextInput::make('total') 
            ->prefix('Rp. ')
            ->readonly()
            ->inlineLabel(),
    ]),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
                TextInput::make('pay_total')
                    ->required()
                    ->numeric(),
            ]);
    }
}
