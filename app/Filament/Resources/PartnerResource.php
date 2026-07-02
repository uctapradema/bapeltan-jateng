<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'MASTER DATA';
    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Partner';
    protected static ?string $pluralModelLabel = 'Partner';
    protected static ?string $navigationLabel = 'Partner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Partner')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Partner')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->nullable()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Nonaktif',
                            ])
                            ->required()
                            ->default('active'),

                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('partners')
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Partner')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('website')
                    ->label('Website')
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Aktif' : 'Nonaktif'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('urutan')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
