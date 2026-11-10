<?php

namespace App\Filament\Resources\Residents;

use App\Filament\Resources\Residents\Pages\ManageResidents;
use App\Models\Resident;
use App\Support\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleManager::enabled('residents');
    }

    public static function canAccess(): bool
    {
        return ModuleManager::enabled('residents');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('property_id')->relationship('property', 'name')->searchable()->preload(),
            TextInput::make('name')->required(),
            TextInput::make('email')->email(),
            TextInput::make('phone'),
            TextInput::make('unit_number'),
            Select::make('lease_status')->options([
                'active' => 'Active',
                'notice' => 'Notice',
                'pending' => 'Pending',
                'former' => 'Former',
            ])->required(),
            Toggle::make('messaging_consent')->visible(fn () => ModuleManager::enabled('messaging_consent')),
            Toggle::make('privacy_restricted')->visible(fn () => ModuleManager::enabled('tenant_privacy')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('property.name')->label('Property'),
                TextColumn::make('unit_number'),
                TextColumn::make('lease_status')->badge(),
                IconColumn::make('messaging_consent')->boolean(),
                IconColumn::make('privacy_restricted')->boolean()->label('Privacy'),
                TextColumn::make('email')->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageResidents::route('/'),
        ];
    }
}
