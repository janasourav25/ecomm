<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\components\ToggleButtons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Group; 
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Placeholder;

use Filament\Forms\Get;
use Filament\Forms\Set;

// use Filament\Forms\Components\Number; // Verify this import
use Filament\Support\Helpers\NumberHelper; // Add this import
// use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;



class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required(),

                        Forms\Components\TextInput::make('order_number')
                        ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'stripe' => 'Stripe',
                                'cod' => 'Cash on Delivery',
                            ]),

                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->nullable(),

                            
                            

                        ToggleButtons::make('order_status')
                            ->inline()
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                                'refund' => 'Refund',
                            ])
                            ->default('new')
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'primary',
                                'cancelled' => 'danger',
                                'refund' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-mark',
                                'refund' => 'heroicon-m-arrow-uturn-left',
                            ]),

                        Select::make('currency')
                            ->options([
                                'inr' => 'INR',
                                'usd' => 'US',
                                'eur' => 'EUR',
                                'gbp' => 'GBP',
                            ])
                            ->default('inr'),

                        Forms\Components\TextInput::make('shipping_amount')
                            ->numeric()
                            ->prefix('INR')
                            ->nullable(),

                        Forms\Components\Select::make('shipping_method')
                            ->options([
                                'dtdc' => 'DTDC',
                                'flipkart' => 'FlipKart',
                                'shiprocket' => 'ShipRokcet',
                            ])
                            ->nullable(),

                        Forms\Components\Textarea::make('notes')
                            ->nullable()
                            ->columnSpanFull()
                            ->default(NULL)
                    ])
                    ->columns(2),

                Section::make('Order Items')->schema([
                    Repeater::make('items')
                        // ->relationship('items')  // its work but i cheked deffrent logic
                        ->relationship()
                        ->schema([
                            Select::make('produsct_id')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->distinct()
                                ->columnSpan(4)
                                ->required()
                                ->reactive()
                               // ------this logic is working but i cheked deffrent logic-------

                                // ->afterStateUpdated(function ($state, callable $set) {
                                //     $productVariant = ProductVariant::find($state);
                                //     if ($productVariant) {
                                //         $set('total', $productVariant->sale_price ?: $productVariant->price);
                                //         $set('price', $productVariant->sale_price ?: $productVariant->price);
                                //     } else {
                                //         $set('total', 0);
                                //         $set('price', 0);
                                //     }
                                // }),
                                // ------------end----------------


                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('product_variant_id', null); // reset variant product changes
                                }),

                            Forms\Components\TextInput::make('quantity')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(1)
                                ->columnSpan(2)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $set('total', $state * $get('price'));
                                }),

                            Forms\Components\TextInput::make('price','INR')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('total')  // Corrected total field name
                                ->numeric()
                                ->required(),

                            Forms\Components\Select::make('product_variant_id')
                            //---------- it's working but i checked different things-------
                                // ->relationship('variant', 'id')
                                // ->nullable()
                                // ->afterStateUpdated(function ($state, callable $set) {
                                //     $productVariant = ProductVariant::find($state);
                                //     if ($productVariant && $productVariant->color) {
                                //         $set('variant_color', $productVariant->color->name ?? 'No color available');
                                //     } else {
                                //         $set('variant_color', 'No color available');
                                //     }
                                // }),

                                // ------------ end ----------------

                                ->label('Product Variant')
                                ->required()
                                ->options(function(callable $get){
                                    $productId = $get('product_id');
                                    if($productId) {
                                        return \App\Models\ProductVariant::where('product_id', $productId)
                                        ->get()
                                        ->mapWithKeys(function($variant){
                                            return [
                                                $variant->id =>"{$variant->sku} - {$variant->color} - {$variant->sku}"
                                            ];
                                        });
                                    }
                                    return [];
                                })
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state, callable $get){
                                    // featch the variant and set the product price automatically
                                    if($state) {
                                        $variant = \App\Models\ProductVariant::find($state);
                                        if ($variant) {
                                            // Check if sale price exists and is not null, otherwise use regular price
                                            $price = $variant->sale_price ?? $variant->price;
                                            $set('price', $price);


                                             // Recalculate total based on new price and current quantity
                                                 $quantity = $get('quantity') ?? 1;
                                                 $set('total', $price * $quantity);
                                        }
                                    }
                                })
                                ->placeholder('Select product Variant'),

                            ToggleButtons::make('status')
                        ])
                            ]),
                // ->columns(15),

                // Grand Total Placeholder
                // Placeholder::make('grand_total_placeholder')
                //     ->label('Grand Total')
                //     ->content(function (Get $get, Set $set) {
                //         $total = 0;
                //         $items = $get('items');  // Ensure you're getting the right 'items' state

                //         if (!$items) {
                //             return \Number::currency($total, 'INR');
                //         }

                //         foreach ($items as $key => $repeater) {
                //             // Safely access the 'total' for each item
                //             $total += $get("items.{$key}.total") ?? 0; // Default to 0 if not set
                //         }

                //         return \Number::currency($total, 'INR');
                //     })
                Placeholder::make('grand_total_placeholder')
                    ->label('Grand Total')
                    ->content(function (Get $get, Set $set) {
                        $total = 0;
                        $items = $get('items');
                        $shippingAmount = $get('shipping_amount') ?? 0;

                        if (!$items) {
                            return \Number::currency($shippingAmount, 'INR');
                        }

                        foreach ($items as $key => $repeater) {
                            $total += $get("items.{$key}.total") ?? 0;
                        }

                        $grandTotal = $total + $shippingAmount;
                        return \Number::currency($grandTotal, 'INR');
                    })
            ]);
}

public static function table(Table $table): Table
{
   return $table
       ->columns([
           Tables\Columns\TextColumn::make('user.name')
               ->label('Customer')
               ->searchable()
               ->sortable(),

           Tables\Columns\TextColumn::make('order_number')
               ->searchable()
               ->sortable(),

           Tables\Columns\TextColumn::make('total')
               ->money('INR')
               ->sortable(),

        //    Tables\Columns\TextColumn::make('order_status')
        //        ->badge()
        //        ->color(fn (string $state): string => match ($state) {
        //            'new' => 'info',
        //            'processing' => 'warning',
        //            'shipped' => 'primary',
        //            'delivered' => 'success',
        //            'cancelled' => 'danger',
        //            default => 'secondary',
        //        })
        //        ->sortable(),

        SelectColumn::make('order_status')
        ->options([
                       'new' => 'New',
                       'processing' => 'Processing',
                       'shipped' => 'Shipped',
                       'delivered' => 'Delivered',
                       'cancelled' => 'Cancelled',
                       'refund' => 'Refund',
                   ])
                   ->searchable()
               ->sortable(),
        


           Tables\Columns\TextColumn::make('payment_status')
               ->badge()
               ->color(fn (string $state): string => match ($state) {
                   'pending' => 'warning',
                   'paid' => 'success',
                   'failed' => 'danger',
                   default => 'secondary',
               })

               
               ->sortable(),

               TextColumn::make('currency')
               ->sortable()
               ->searchable(),

           Tables\Columns\TextColumn::make('payment_method')
               ->sortable(),

               

           Tables\Columns\TextColumn::make('created_at')
               ->label('Order Date')
               ->dateTime()
               ->sortable(),
       ])
       ->filters([
           Tables\Filters\SelectFilter::make('order_status')
               ->options([
                   'new' => 'New',
                   'processing' => 'Processing',
                   'shipped' => 'Shipped',
                   'delivered' => 'Delivered',
                   'cancelled' => 'Cancelled',
               ]),
               
           
           Tables\Filters\SelectFilter::make('payment_status')
               ->options([
                   'pending' => 'Pending',
                   'paid' => 'Paid',
                   'failed' => 'Failed',
               ]),
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

public static function createOrderItem(ProductVariant $productVariant, $quantity): OrderItem
{
    // Calculate the total amount
    $totalAmount = $productVariant->price * $quantity;

    // Create a new OrderItem with the required data
    $orderItem = new OrderItem();
    $orderItem->product_variant_id = $productVariant->id;
    $orderItem->quantity = $quantity;
    $orderItem->price = $productVariant->price;
    $orderItem->total = $totalAmount;
    
    // Generate a unique order number
    $orderItem->order_number = 'ORD-' . Str::uuid();  // Or any other logic to generate order number

    $orderItem->save();

    return $orderItem;
}


public static function getNavigationBadge(): ?string{
    return static::getModel()::count();
}

public static function getNavigationBadgeColor(): string|array|null{
    return static::getModel()::count()>4 ? 'success' : 'danger';
}


public static function getRelations(): array
{
    return [
        AddressRelationManager::class,
    ];
} 

public static function getPages(): array
{
    return [
        'index' => Pages\ListOrders::route('/'),
        'create' => Pages\CreateOrder::route('/create'),
        'edit' => Pages\EditOrder::route('/{record}/edit'),
    ];
}
}