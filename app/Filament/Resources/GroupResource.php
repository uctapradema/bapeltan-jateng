<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Models\Group;
use App\Models\Kegiatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'DATA';

    protected static ?string $modelLabel = 'Group';
    protected static ?string $pluralModelLabel = 'Group';
    protected static ?string $navigationLabel = 'Group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Group')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Group')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('group_link')
                            ->label('Link Group')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('group_username')
                            ->label('Username Group')
                            ->placeholder('misal: grupku123')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ])
                            ->required()
                            ->default('active'),

                        // 🔹 Dropdown pilih kegiatan
                        Forms\Components\Select::make('kegiatan_id')
                            ->label('Pilih Jenis Kegiatan')
                            ->relationship('kegiatan', 'nama_pelatihan')
                            ->preload()
                            ->searchable()
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Group')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group_link')
                    ->label('Link')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\TextColumn::make('group_username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                // Tambahkan kolom kegiatan
                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')
                    ->label('Jenis Kegiatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date()
                    ->sortable(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
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
        return [
            // Bisa ditambahkan RelationManager untuk Kegiatans jika perlu
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
