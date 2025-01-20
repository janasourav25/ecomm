<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Tabs::make('Product')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Basic Information')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                    
                                    Forms\Components\TextInput::make('slug')
                                        ->required()
                                        ->unique('products', 'slug', ignorable: fn ($record) => $record),
                                ]),

                            // Forms\Components\Select::make('category_id')
                            //     ->relationship('category', 'name')
                            //     ->required(),

                            Forms\Components\Select::make('brand_id')
                                ->relationship('brand', 'name')
                                ->required(),

                            Forms\Components\Textarea::make('short_description')
                                ->rows(3),

                            Forms\Components\RichEditor::make('description')
                                ->required(),

                            Forms\Components\FileUpload::make('default_images')
                                ->image()
                                ->multiple()
                                ->maxFiles(5)
                                ->directory('products')
                                ->enableReordering(),

                            Forms\Components\Toggle::make('status')
                                ->label('Active')
                                ->default(true),

                            Forms\Components\Toggle::make('featured')
                                ->label('Featured Product'),
                        ]),

                    Forms\Components\Tabs\Tab::make('Variants')
                        ->schema([
                            Forms\Components\Repeater::make('variants')
                                ->relationship('variants')
                                ->schema([
                                    Forms\Components\TextInput::make('sku')
                                        ->required()
                                        ->unique('product_variants', 'sku', ignorable: fn ($record) => $record),

                                    Forms\Components\TextInput::make('price')
                                        ->numeric()
                                        ->required()
                                        ->prefix('$'),

                                    Forms\Components\TextInput::make('stock')
                                        ->numeric()
                                        ->required(),

                                    Forms\Components\FileUpload::make('images')
                                        ->image()
                                        ->multiple()
                                        ->maxFiles(5)
                                        ->directory('variants'),

                                    Forms\Components\Repeater::make('attributes')
                                        ->schema([
                                            Forms\Components\Select::make('attribute')
                                                ->options(fn () => Attribute::pluck('name', 'id'))
                                                ->required(),
                                            Forms\Components\TextInput::make('value')
                                                ->required(),
                                        ]),
                                ])
                                ->columns(2),
                        ]),
                ])
                ->columnSpan('full'),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('default_images')
                ->circular()
                ->label('Image'),

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('category.name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('brand.name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('variants_count')
                ->counts('variants')
                ->label('Variants'),

            Tables\Columns\BooleanColumn::make('status')
                ->label('Active'),

            Tables\Columns\BooleanColumn::make('featured')
                ->label('Featured'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('category')
                ->relationship('category', 'name'),

            Tables\Filters\SelectFilter::make('brand')
                ->relationship('brand', 'name'),

            Tables\Filters\TernaryFilter::make('status')
                ->label('Active'),

            Tables\Filters\TernaryFilter::make('featured')
                ->label('Featured'),
        ])
        ->actions([
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
        'index' => Pages\ListProducts::route('/'),
        'create' => Pages\CreateProduct::route('/create'),
        'edit' => Pages\EditProduct::route('/{record}/edit'),
    ];
}
}