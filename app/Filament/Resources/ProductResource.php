<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tab;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    // Form Configuration for Product Creation/Update
    public static function form(Forms\Form $form): Forms\Form
    {
    //     return $form
    //         ->schema([
    //             Forms\Components\Tabs::make('Product')
    //                 ->tabs([
    //                     Forms\Components\Tabs\Tab::make('Basic Information')
    //                         ->schema([
    //                             Forms\Components\Grid::make(2)
    //                                 ->schema([
    //                                     Forms\Components\TextInput::make('name')
    //                                         ->required()
    //                                         ->reactive()
    //                                         ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

    //                                     Forms\Components\TextInput::make('slug')
    //                                         ->required()
    //                                         ->unique('products', 'slug', ignorable: fn ($record) => $record),
    //                                 ]),

    //                             Forms\Components\Select::make('category_id')
    //                                 ->relationship('category', 'name')  // Relationship with the Category model
    //                                 ->required()  // Make sure this is required
    //                                 ->label('Category'),

    //                             Forms\Components\Select::make('brand_id')
    //                                 ->relationship('brand', 'name')
    //                                 ->required()  // Make sure this is required
    //                                 ->label('Brand'),

    //                             Forms\Components\Textarea::make('short_description')
    //                                 ->rows(3),

    //                             Forms\Components\RichEditor::make('description')
    //                                 ->required(),

    //                             Forms\Components\FileUpload::make('default_images')
    //                                 ->image()
    //                                 ->multiple()
    //                                 ->maxFiles(5)
    //                                 ->directory('products')
    //                                 ->enableReordering(),

    //                             Forms\Components\Toggle::make('status')
    //                                 ->label('Active')
    //                                 ->default(true),

    //                             Forms\Components\Toggle::make('featured')
    //                                 ->label('Featured Product'),
    //                         ]),

    //                     Forms\Components\Tabs\Tab::make('Variants')
    //                         ->schema([
    //                             Forms\Components\Repeater::make('variants')
    //                                 ->relationship('variants')
    //                                 ->schema([
    //                                     Forms\Components\TextInput::make('sku')
    //                                         ->required()
    //                                         ->unique('product_variants', 'sku', ignorable: fn ($record) => $record),

    //                                     Forms\Components\TextInput::make('price')
    //                                         ->numeric()
    //                                         ->required()
    //                                         ->prefix('INR')
    //                                         ->afterStateUpdated(function ($state, callable $set, $get) {
    //                                             // When the price is updated, calculate the total price and adjust it
    //                                             $variant = $get('variant');  // assuming variant is an existing model
    
    //                                             if ($variant) {
    //                                                 $set('total', $variant->sale_price ?: $variant->price);  // set total as sale_price or regular price
    //                                             } else {
    //                                                 $set('total', 0);
    //                                             }
    //                                         }),

    //                                         Forms\Components\TextInput::make('sale_price')
    //                                             ->numeric()
    //                                             ->prefix('INR')
    //                                             ->label('Sale Price')   // Sale price field
    //                                             ->nullable(),           // Optional field

    //                                     Forms\Components\TextInput::make('quantity')
    //                                         ->numeric()
    //                                         ->required(),

    //                                     Forms\Components\FileUpload::make('images')
    //                                         ->image()
    //                                         ->multiple()
    //                                         ->maxFiles(5)
    //                                         ->directory('variants'),

    //                                         Forms\Components\TextInput::make('color')
    //                                             ->required()
    //                                             ->label('Color'),       // Color field

    //                                         Forms\Components\TextInput::make('size')
    //                                             ->required()
    //                                             ->label('Size'),        // Size field
    //                                 ])
    //                                 ->columns(2),
                                    
    //                         ]),

    //                              // Add a new tab for status, stock, and sale options
    //                 Forms\Components\Tabs\Tab::make('Product Status')
    //                 ->schema([
    //                     Forms\Components\Toggle::make('status')
    //                         ->label('Status')
    //                         ->default(true),

    //                     Forms\Components\Toggle::make('in_stock')
    //                         ->label('In Stock')
    //                         ->default(true),

    //                     Forms\Components\Toggle::make('on_sale')
    //                         ->label('On Sale')
    //                         ->default(false),
    //                 ]),

    //         ])
    //         ->columnSpan('full'),
    //         ]);
    // }

    //-------------------

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

                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')  
                            ->required()
                            ->label('Category'),

                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->required()
                            ->label('Brand'),

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
                                    ->prefix('INR')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        // When the price is updated, calculate the total price and adjust it
                                        $variantId = $get('id'); // Get the variant ID

                                        if ($variantId) {
                                            $variant = \App\Models\ProductVariant::find($variantId);
                                            if ($variant) {
                                                // Set total price as sale_price or price
                                                $set('total', $variant->sale_price ?: $variant->price);
                                            }
                                        } else {
                                            $set('total', 0);
                                        }
                                    }),

                                Forms\Components\TextInput::make('sale_price')
                                    ->numeric()
                                    ->prefix('INR')
                                    ->label('Sale Price')
                                    ->nullable(),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\FileUpload::make('images')
                                    ->image()
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->directory('variants'),

                                Forms\Components\TextInput::make('color')
                                    ->required()
                                    ->label('Color'),

                                Forms\Components\TextInput::make('size')
                                    ->required()
                                    ->label('Size'),
                            ])
                            ->columns(2),
                    ]),

                Forms\Components\Tabs\Tab::make('Product Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Status')
                            ->default(true),

                        Forms\Components\Toggle::make('in_stock')
                            ->label('In Stock')
                            ->default(true),

                        Forms\Components\Toggle::make('on_sale')
                            ->label('On Sale')
                            ->default(false),
                    ]),

            ])
            ->columnSpan('full'),
    ]);
}


    // Table Configuration for Product List
    public static function table(Tables\Table $table): Tables\Table
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Pages for Product Creation, Editing, and Listing
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
