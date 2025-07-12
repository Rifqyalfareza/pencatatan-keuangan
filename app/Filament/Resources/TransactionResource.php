<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?string $pluralLabel = 'List Transactions';
    protected static ?string $activeNavigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('amout')
                    ->required()
                    ->prefix('Rp')
                    ->numeric()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\Select::make('categories_id')
                    ->relationship('categories', 'name')
                    ->required()
                    ->columnSpan(2)
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Category Name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Select::make('type')
                    ->options([
                        'expense' => 'Expense',
                        'income' => 'Income',
                    ])
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amout')
                    ->money('IDR', true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('Category')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn () => '')
                    ->numeric()
                    ->sortable()
                    ->icon(fn($record) => $record->type === 'expense' ? 'heroicon-o-arrow-down' : 'heroicon-o-arrow-up')
                    ->color(fn($record) => $record->type === 'expense' ? 'danger' : 'success')
                    ->tooltip(fn($record) => $record->type === 'expense' ? 'Expense' : 'Income'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            // 'create' => Pages\CreateTransaction::route('/create'),
            // 'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
