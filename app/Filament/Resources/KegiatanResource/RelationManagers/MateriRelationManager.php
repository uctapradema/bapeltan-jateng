<?php

namespace App\Filament\Resources\KegiatanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MateriRelationManager extends RelationManager
{
    protected static string $relationship = 'materis';

    protected static ?string $title = 'Materi Pelatihan';

    protected static ?string $recordTitleAttribute = 'judul';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Materi')
                ->schema([
                    Forms\Components\TextInput::make('judul')
                        ->required()
                        ->label('Judul Materi')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\Select::make('tipe')
                        ->label('Tipe Materi')
                        ->options([
                            'video_url' => 'Video (URL/Embed)',
                            'video_file' => 'Video (File Upload)',
                            'dokumen' => 'Dokumen (PDF/PPT/DOC)',
                            'gambar' => 'Gambar',
                        ])
                        ->required()
                        ->default('video_url')
                        ->reactive()
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('urutan')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0)
                        ->columnSpan(1),
                ])->columns(2),

            Forms\Components\Section::make('Konten')
                ->schema([
                    Forms\Components\TextInput::make('url')
                        ->label('URL Video')
                        ->placeholder('https://www.youtube.com/watch?v=...')
                        ->helperText('YouTube, Vimeo, Google Drive, atau URL lainnya')
                        ->visible(fn (Forms\Get $get) => $get('tipe') === 'video_url')
                        ->required(fn (Forms\Get $get) => $get('tipe') === 'video_url')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('file_path')
                        ->label('Upload File')
                        ->directory('materis')
                        ->visibility('public')
                        ->maxSize(51200) // 50KB
                        ->acceptedFileTypes([
                            'video/mp4', 'video/webm', 'video/ogg',
                            'application/pdf',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/jpeg', 'image/png', 'image/webp',
                        ])
                        ->visible(fn (Forms\Get $get) => in_array($get('tipe'), ['video_file', 'dokumen', 'gambar']))
                        ->required(fn (Forms\Get $get) => in_array($get('tipe'), ['video_file', 'dokumen', 'gambar']))
                        ->columnSpanFull(),
                ])->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul')
            ->columns([
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->width(50),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video_url' => 'info',
                        'video_file' => 'success',
                        'dokumen' => 'warning',
                        'gambar' => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'video_url' => 'Video URL',
                        'video_file' => 'Video File',
                        'dokumen' => 'Dokumen',
                        'gambar' => 'Gambar',
                    }),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Materi'),
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
