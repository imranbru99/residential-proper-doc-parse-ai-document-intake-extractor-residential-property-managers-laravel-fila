<?php

namespace App\Filament\Resources\FeatureModules;

use App\Filament\Resources\FeatureModules\Pages\ManageFeatureModules;
use App\Models\FeatureModule;
use App\Support\ModuleManager;
use BackedEnum;
use Filament\Actions\EditAction;
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

class FeatureModuleResource extends Resource
{
    protected static ?string $model = FeatureModule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Feature Modules';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')->disabled()->dehydrated(),
            TextInput::make('name')->required(),
            Textarea::make('description')->columnSpanFull(),
            Toggle::make('enabled')->label('Module Enabled'),
            Toggle::make('is_core')->disabled()->dehydrated(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('key')->badge(),
                IconColumn::make('enabled')->boolean()->label('ON'),
                IconColumn::make('is_core')->boolean()->label('Core'),
                TextColumn::make('description')->limit(50),
            ])
            ->recordActions([
                EditAction::make()->after(fn () => ModuleManager::refresh()),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFeatureModules::route('/'),
        ];
    }
}
