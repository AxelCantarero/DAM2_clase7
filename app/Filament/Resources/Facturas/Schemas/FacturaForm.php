<?php

namespace App\Filament\Resources\Facturas\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;

use Filament\Schemas\Components\Grid;

class FacturaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('numero_factura')
                    ->required(),

                TextInput::make('cliente')
                    ->required(),

                DatePicker::make('fecha')
                    ->required(),

                Repeater::make('detalles')

                    ->relationship()

                    ->live()

                    ->afterStateUpdated(function ($state, callable $set) {

                        $total = 0;

                        if ($state) {

                            foreach ($state as $detalle) {

                                $cantidad = (float) ($detalle['cantidad'] ?? 0);
                                $precio = (float) ($detalle['precio_unitario'] ?? 0);

                                $total += $cantidad * $precio;
                            }
                        }

                        $set('total', $total);
                    })

                    ->schema([

                        Grid::make(4)
                            ->schema([

                                TextInput::make('producto')
                                    ->required(),

                                TextInput::make('cantidad')
                                    ->numeric()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                        $cantidad = (float) $get('cantidad');
                                        $precio = (float) $get('precio_unitario');

                                        $set('subtotal', $cantidad * $precio);
                                    }),

                                TextInput::make('precio_unitario')
                                    ->numeric()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                        $cantidad = (float) $get('cantidad');
                                        $precio = (float) $get('precio_unitario');

                                        $set('subtotal', $cantidad * $precio);
                                    }),

                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                            ])

                    ])

                    ->columns(1)

                    ->addActionLabel('Agregar Producto'),

                TextInput::make('total')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()

            ]);
    }
}