<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\Request;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Ecommerce';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) Product::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description'),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                TextInput::make('stock')
                    ->numeric()
                    ->maxValue(100)
                    ->required(),
                FileUpload::make('image')->image()
                    ->directory('products')
                    ->disk(config('filesystems.default'))
                    ->maxSize(5 * 1024)
                    ->preserveFilenames(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image') // Product Image
                    ->label('Image')
                    ->disk(config('filesystems.default'))
                    ->height(50)
                    ->width(50)
                    ->defaultImageUrl(asset('images/default.png'))
                    ->circular()
                    ->getStateUsing(function ($record) {
                        return $record->image ? $record->image : asset('images/default.png');
                    }),
                TextColumn::make('name') // Product Name
                    ->label('Product Name')
                    ->default('asc')
                    ->sortable() // Make it sortable
                    ->searchable(), // Make it searchable

                TextColumn::make('description') // Product Description
                    ->label('Description')
                    ->limit(50), // Limit the text length for display

                TextColumn::make('price') // Product Price
                    ->label('Price')
                    ->money('PHP') // Format as currency (USD or your preferred currency)
                    ->sortable(),

                TextColumn::make('stock') // Product Stock Quantity
                    ->label('Stock')
                    ->sortable()
                    ->alignCenter(), // Center align the stock quantity

            ])
            ->filters([
                // Define your filters here
            ])
            ->actions([
                ViewAction::make('view'),
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-trash')
                    ->modalIconColor('warning')
                    ->modalHeading(__('Confirm Delete'))
                    ->modalDescription(__('Are you sure you want to delete this product?'))
                    ->action(function ($record) {
                        $record->delete();
                        Notification::make()
                            ->title('Product Deleted')
                            ->body('The product has been successfully deleted.')
                            ->success()
                            ->send();
                    }),
                RestoreAction::make(),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }

    public static function query(Request $request)
    {
        $request->session()->forget('filament.search');

        return parent::query($request);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make()
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('description'),
                        TextEntry::make('stock'),
                        ImageEntry::make('image')
                            ->circular()
                            ->disk(config('filesystems.default')),
                    ]),
            ]);
    }
}
