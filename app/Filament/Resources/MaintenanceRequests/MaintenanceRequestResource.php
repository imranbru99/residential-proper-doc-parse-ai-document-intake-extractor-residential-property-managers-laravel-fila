<?php

namespace App\Filament\Resources\MaintenanceRequests;

use App\Filament\Resources\MaintenanceRequests\Pages\ManageMaintenanceRequests;
use App\Models\MaintenanceRequest;
use App\Support\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class MaintenanceRequestResource extends Resource
{
    protected static ?string $model = MaintenanceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleManager::enabled('maintenance_requests');
    }

    public static function canAccess(): bool
    {
        return ModuleManager::enabled('maintenance_requests');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('reference')->required(),
            Select::make('property_id')->relationship('property', 'name')->searchable()->preload(),
            Select::make('resident_id')->relationship('resident', 'name')->searchable()->preload(),
            TextInput::make('resident_name')->required(),
            TextInput::make('resident_email')->email(),
            TextInput::make('resident_phone'),
            TextInput::make('unit_number'),
            Select::make('category')->options([
                'general' => 'General',
                'plumbing' => 'Plumbing',
                'hvac' => 'HVAC',
                'electrical' => 'Electrical',
                'appliance' => 'Appliance',
                'lease_renewal' => 'Lease / Admin',
                'emergency' => 'Emergency',
            ])->required(),
            Select::make('priority')->options([
                'low' => 'Low',
                'normal' => 'Normal',
                'high' => 'High',
                'emergency' => 'Emergency',
            ])->required(),
            Select::make('status')->options([
                'open' => 'Open',
                'scheduled' => 'Scheduled',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ])->required(),
            Select::make('assigned_vendor_id')->relationship('vendor', 'name')->searchable()->preload(),
            Toggle::make('messaging_consent')->visible(fn () => ModuleManager::enabled('messaging_consent')),
            Toggle::make('fair_housing_flag')->visible(fn () => ModuleManager::enabled('fair_housing_guard')),
            Textarea::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable()->sortable(),
                TextColumn::make('resident_name')->searchable(),
                TextColumn::make('property.name')->label('Property'),
                TextColumn::make('category')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('vendor.name')->label('Vendor'),
                IconColumn::make('fair_housing_flag')->boolean()->label('FH'),
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
            'index' => ManageMaintenanceRequests::route('/'),
        ];
    }
}
