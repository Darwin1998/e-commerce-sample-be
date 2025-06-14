<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Ecommerce';

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Order Form')
                ->columnSpan(5)
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Customer Details')
                        ->schema([
                            Forms\Components\Grid::make(2) // This will create a grid with 2 columns
                                ->schema([
                                    Forms\Components\Select::make('customer_id')
                                        ->relationship('customer', 'name')
                                        ->disabled(fn ($record) => $record !== null)
                                        ->required()
                                        ->reactive() // Make the field reactive
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            // When a customer is selected, set the billing_name to the selected customer's name
                                            if ($state) {
                                                $customer = \App\Models\Customer::find($state);
                                                $set('billing_name', $customer ? $customer->name : ''); // Set the billing_name
                                            } else {
                                                $set('billing_name', ''); // Clear the billing_name if no customer is selected
                                            }
                                        }),

                                    Forms\Components\TextInput::make('shipping_address')
                                        ->disabled(fn ($record) => $record !== null)
                                        ->required()
                                        ->maxLength(1000),

                                    Forms\Components\TextInput::make('billing_address')
                                        ->disabled(fn ($record) => $record !== null)
                                        ->maxLength(1000),

                                    Forms\Components\TextInput::make('billing_name')
                                        ->disabled(fn ($record) => $record !== null)
                                        ->dehydrated()
                                        ->readOnly(),
                                    Forms\Components\TextInput::make('billing_phone')
                                        ->disabled(fn ($record) => $record !== null)
                                        ->dehydrated(),
                                ])
                                ->columnSpan(2), // This spans the 2 columns evenly for the above fields

                            Forms\Components\MarkdownEditor::make('notes')
                                ->disabled(fn ($record) => $record !== null)
                                ->columnSpan('full'),
                        ]),

                    Forms\Components\Tabs\Tab::make('Order Details')
                        ->schema([
                            Forms\Components\TextInput::make('order_number')
                                ->default(fn () => 'OR-'.str_pad((string) (Order::count() + 1), 9, '0', STR_PAD_LEFT)) // Ensure it's a string
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->maxLength(32)
                                ->unique(Order::class, 'order_number', ignoreRecord: true),
                            Forms\Components\Repeater::make('orderProducts')
                                ->disabled(fn ($record) => $record !== null)
                                ->relationship()
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->relationship('product', 'name')
                                            ->disabled(fn ($record) => $record !== null)
                                            ->required()
                                            ->reactive() // Make the product selection reactive
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state) {
                                                    $product = \App\Models\Product::find($state);
                                                    $set('price', $product ? $product->price : 0);
                                                    $set('stock', $product ? $product->stock : 0);
                                                }
                                            }),
                                        Forms\Components\Placeholder::make('Current Stock')
                                            ->content(function ($record, $get): HtmlString {
                                                $stock = $record->product->stock ?? $get('stock');

                                                return new HtmlString("<strong>Stock:</strong> {$stock}");
                                            }),

                                        Forms\Components\TextInput::make('quantity')
                                            ->disabled(fn ($record) => $record !== null)
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->maxValue(function (callable $get) {
                                                return $get('stock');
                                            })
                                            ->required(),

                                        Forms\Components\TextInput::make('price')
                                            ->disabled(fn ($record) => $record !== null)
                                            ->numeric()
                                            ->required()
                                            ->readOnly()
                                            ->default(0),
                                    ]),
                                ])
                                ->dehydrated(true)
                                ->columns(3),

                            Forms\Components\ToggleButtons::make('status')
                                ->inline()
                                ->disabled(fn ($record) => $record?->status === OrderStatus::Cancelled->value)
                                ->options(OrderStatus::class)
                                ->required(),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order Number')
                    ->default('asc')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('billing_name')
                    ->label('Customer Name')
                    ->default('asc')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Order Status')
                    ->default('asc')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => OrderStatus::tryFrom($state)?->getLabel() ?? 'Unknown')
                    ->color(fn ($state) => OrderStatus::tryFrom($state)?->getColor())
                    ->icon(fn ($state) => OrderStatus::tryFrom($state)?->getIcon())
                    ->searchable(),

                TextColumn::make('total_price')
                    ->label('Amount')
                    ->default('asc')
                    ->sortable()
                    ->money('PHP') // Format as currency (USD or your preferred currency)
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Order Status')
                    ->options(
                        collect(OrderStatus::cases())
                            ->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])
                            ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('order_number'),
                        TextEntry::make('billing_name'),
                        TextEntry::make('billing_address'),
                        TextEntry::make('total_price')
                            ->money('PHP'),
                        Section::make('Products')
                            ->schema([
                                RepeatableEntry::make('orderProducts')
                                    ->label('')
                                    ->schema([
                                        Section::make()
                                            ->heading('Product')
                                            ->schema([
                                                TextEntry::make('product.name')
                                                    ->label('Name'),
                                                TextEntry::make('price')
                                                    ->money('PHP'),
                                                TextEntry::make('quantity'),
                                            ])
                                            ->collapsible(),
                                    ])->contained(false),
                            ])
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest('created_at'); // TODO: Change the autogenerated stub
    }
}
