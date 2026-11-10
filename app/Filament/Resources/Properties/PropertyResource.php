<?php

namespace App\Filament\Resources\Properties;

use App\Filament\Resources\Properties\Pages\ManageProperties;
use App\Models\Property;
use App\Support\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleManager::enabled('properties');
    }

    public static function canAccess(): bool
    {
        return ModuleManager::enabled('properties');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->required()->maxLength(50),
            TextInput::make('name')->required(),
            TextInput::make('address')->columnSpanFull(),
            TextInput::make('city'),
            TextInput::make('state')->maxLength(2),
            TextInput::make('postal_code'),
            TextInput::make('unit_count')->numeric()->default(0),
            Select::make('property_type')->options([
                'multifamily' => 'Multifamily',
                'garden' => 'Garden Style',
                'highrise' => 'High-rise',
                'townhome' => 'Townhome',
                'single_family' => 'Single Family Portfolio',
            ])->required(),
            Select::make('status')->options([
                'active' => 'Active',
                'lease_up' => 'Lease-up',
                'renovation' => 'Renovation',
                'inactive' => 'Inactive',
            ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('city'),
                TextColumn::make('state'),
                TextColumn::make('unit_count'),
                TextColumn::make('property_type')->badge(),
                TextColumn::make('status')->badge(),
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
            'index' => ManageProperties::route('/'),
        ];
    }
}
