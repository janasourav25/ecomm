<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariantResource\Pages;
use App\Filament\Resources\ProductVariantResource\RelationManagers;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\OrderItem; 

class ProductVariantResource extends Resource
{
    protected static ?string $model = ProductVariant::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('sku')
                //     ->required()
                //     ->unique('product_variants', 'sku')
                //     ->label('SKU'),

                // SKU field for Product Variant
                Forms\Components\TextInput::make('sku')
                    ->required()
                    // Check uniqueness conditionally based on whether we are editing or creating
                    ->unique('product_variants', 'sku', ignorable: fn ($record) => $record) // Ignore validation for the current record during edit
                    ->label('SKU'),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->disable()
                    ->dehydrated()
                    ->prefix('INR')
                    ->label('Price'),

                    Forms\Components\TextInput::make('sale_price')
                    ->numeric()
                    ->prefix('INR')
                    ->label('Sale Price')   // Sale price field
                    ->disable()
                    ->dehydrated()
                    ->nullable(),           // Optional field

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->label('Quantity'),

                Forms\Components\FileUpload::make('images')
                    ->image()
                    ->multiple()
                    ->maxFiles(5)
                    ->directory('variants')
                    ->label('Variant Images'),

                    Forms\Components\TextInput::make('color')
                    ->required()
                    ->label('Color'),       // Color field

                Forms\Components\TextInput::make('size')
                    // ->required()
                    ->label('Size'),        // Size field


                           // Adding new fields
                Forms\Components\Toggle::make('status')
                ->label('Status')
                ->default(true),

            Forms\Components\Toggle::make('in_stock')
                ->label('In Stock')
                ->default(true),

            Forms\Components\Toggle::make('on_sale')
                ->label('On Sale')
                ->default(false),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->sortable()
                    ->label('SKU'),

                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable()
                    ->label('Price'),

                Tables\Columns\TextColumn::make('sale_price')
                    ->money('INR')  
                    ->sortable()
                    ->label('Sale Price'),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->label('Quantity'),

                Tables\Columns\ImageColumn::make('images')
                    ->label('Images')
                    ->circular(),

                    // Add new columns for the status and in_stock
                Tables\Columns\BooleanColumn::make('status')
                ->label('Status')
                ->sortable(),

            Tables\Columns\BooleanColumn::make('in_stock')
                ->label('In Stock')
                ->sortable(),

            Tables\Columns\BooleanColumn::make('on_sale')
                ->label('On Sale')
                ->sortable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label('Product'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

     

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductVariants::route('/'),
            'create' => Pages\CreateProductVariant::route('/create'),
            'edit' => Pages\EditProductVariant::route('/{record}/edit'),
        ];
    }
}