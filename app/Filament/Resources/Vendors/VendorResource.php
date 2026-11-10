<?php

namespace App\Filament\Resources\Vendors;

use App\Filament\Resources\Vendors\Pages\ManageVendors;
use App\Models\Vendor;
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

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleManager::enabled('vendors');
    }

    public static function canAccess(): bool
    {
        return ModuleManager::enabled('vendors');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
            TextInput::make('company_name'),
            TextInput::make('email')->email(),
            TextInput::make('phone'),
            TextInput::make('license_number'),
            Select::make('trade_specialty')->options([
                'general_maintenance' => 'General Maintenance',
                'plumbing' => 'Plumbing',
                'hvac' => 'HVAC',
                'electrical' => 'Electrical',
                'appliance' => 'Appliance',
                'make_ready' => 'Make-Ready',
            ])->required(),
            Select::make('status')->options([
                'available' => 'Available',
                'on_job' => 'On Job',
                'off_duty' => 'Off Duty',
            ])->required(),
            TextInput::make('rating')->numeric()->minValue(0)->maxValue(5)->step(0.1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('company_name'),
                TextColumn::make('trade_specialty')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('rating'),
                TextColumn::make('phone'),
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
            'index' => ManageVendors::route('/'),
        ];
    }
}
