<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->maxlength(255)
                        ->required(),
                    Forms\Components\TextInput::make('last_name')
                        ->maxlength(255)
                        ->required(),
                ])->columns(2),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxlength(20),
                
                Forms\Components\Textarea::make('address')
                    ->required(),
                
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('city')
                        ->required(),
                    Forms\Components\TextInput::make('state')
                        ->required(),
                    Forms\Components\TextInput::make('postal_code')
                        ->maxlength(10)
                        ->required(),
                ])->columns(3),

                Forms\Components\Select::make('country')
                    ->required()
                    ->options([
                        'IN' => 'India',
                        'US' => 'United States',
                        // Add more countries
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('city')
            // ->columns([
            //     Tables\Columns\TextColumn::make('city'),
            // ])
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Name')
                    ->formatStateUsing(fn ($state, $record) => $record->first_name . ' ' . $record->last_name),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('country')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
