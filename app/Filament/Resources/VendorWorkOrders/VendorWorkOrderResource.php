<?php

namespace App\Filament\Resources\VendorWorkOrders;

use App\Filament\Resources\VendorWorkOrders\Pages\ManageVendorWorkOrders;
use App\Models\VendorWorkOrder;
use App\Support\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class VendorWorkOrderResource extends Resource
{
    protected static ?string $model = VendorWorkOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Vendor Work Orders';

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleManager::enabled('vendor_work_orders');
    }

    public static function canAccess(): bool
    {
        return ModuleManager::enabled('vendor_work_orders');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('work_order_number')->required(),
            TextInput::make('title')->required()->columnSpanFull(),
            Select::make('maintenance_request_id')->relationship('maintenanceRequest', 'reference')->searchable()->preload(),
            Select::make('document_intake_id')->relationship('documentIntake', 'reference')->searchable()->preload(),
            Select::make('vendor_id')->relationship('vendor', 'name')->searchable()->preload(),
            Select::make('property_id')->relationship('property', 'name')->searchable()->preload(),
            Select::make('status')->options([
                'draft' => 'Draft',
                'ready_to_dispatch' => 'Ready to Dispatch',
                'scheduled' => 'Scheduled',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
            ])->required(),
            DateTimePicker::make('scheduled_at'),
            TextInput::make('unit_number'),
            TextInput::make('site_address')->columnSpanFull(),
            Textarea::make('scope_of_work')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('work_order_number')->searchable()->sortable(),
                TextColumn::make('title')->limit(40),
                TextColumn::make('vendor.name')->label('Vendor'),
                TextColumn::make('property.name')->label('Property'),
                TextColumn::make('status')->badge(),
                TextColumn::make('scheduled_at')->dateTime(),
                TextColumn::make('unit_number'),
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
            'index' => ManageVendorWorkOrders::route('/'),
        ];
    }
}
