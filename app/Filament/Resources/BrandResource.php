<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('slug', Str::slug($state));
                                }),

                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->maxLength(255),
                        ]),

                    // Forms\Components\RichEditor::make('description')
                    //     ->maxLength(65535)
                    //     ->columnSpan('full'),

                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('brands')
                        ->maxSize(1024)
                        ->columnSpan('full'),

                    // Forms\Components\TextInput::make('website')
                    //     ->url()
                    //     ->maxLength(255),

                    Forms\Components\Grid::make(2)
                        ->schema([
                            // Forms\Components\Toggle::make('is_featured')
                            //     ->label('Featured')
                            //     ->default(false),

                            Forms\Components\Toggle::make('status')
                                ->label('Active')
                                ->default(true),
                        ]),
                ])
                ->columnSpan([
                    'sm' => 2,
                    'lg' => 2,
                ]),

            Forms\Components\Card::make()
                ->schema([
                    // Forms\Components\TextInput::make('meta_title')
                    //     ->maxLength(255),

                    // Forms\Components\Textarea::make('meta_description')
                    //     ->maxLength(255),
                ])
                ->columnSpan([
                    'sm' => 1,
                    'lg' => 1,
                ]),
        ])
        ->columns([
            'sm' => 3,
            'lg' => 3,
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\ImageColumn::make('image')
                ->square()
                ->size(40),

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

            // Tables\Columns\TextColumn::make('website')
            //     ->url()
            //     ->openUrlInNewTab(),

            // Tables\Columns\BooleanColumn::make('is_featured')
            //     ->label('Featured')
            //     ->sortable(),

            Tables\Columns\BooleanColumn::make('status')
                ->label('Active')
                ->sortable(),

            Tables\Columns\TextColumn::make('products_count')
                ->counts('products')
                ->label('Products'),

            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            // Tables\Filters\TernaryFilter::make('is_featured')
            //     ->label('Featured'),
            Tables\Filters\TernaryFilter::make('status')
                ->label('Active'),
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

public static function getRelations(): array
{
    return [
        //
    ];
}

public static function getPages(): array
{
    return [
        'index' => Pages\ListBrands::route('/'),
        'create' => Pages\CreateBrand::route('/create'),
        'edit' => Pages\EditBrand::route('/{record}/edit'),
    ];
}
}