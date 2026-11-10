<?php

namespace App\Filament\Resources\Approvals;

use App\Filament\Resources\Approvals\Pages\ManageApprovals;
use App\Models\Approval;
use App\Services\DocumentExtractionService;
use App\Support\ModuleManager;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ApprovalResource extends Resource
{
    protected static ?string $model = Approval::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|UnitEnum|null $navigationGroup = 'Document Operations';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleManager::enabled('human_approval');
    }

    public static function canAccess(): bool
    {
        return ModuleManager::enabled('human_approval');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')->options([
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
            ])->required(),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('documentIntake.reference')->label('Document')->searchable(),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                }),
                TextColumn::make('requester.name')->label('Requested By'),
                TextColumn::make('reviewer.name')->label('Reviewed By'),
                TextColumn::make('notes')->limit(40),
                TextColumn::make('reviewed_at')->dateTime(),
            ])
            ->recordActions([
                Action::make('approveNow')
                    ->label('Approve')
                    ->color('success')
                    ->visible(fn (Approval $record) => $record->status === 'pending')
                    ->action(function (Approval $record) {
                        app(DocumentExtractionService::class)->approve($record->documentIntake, auth()->id());
                        Notification::make()->title('Approved')->success()->send();
                    }),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageApprovals::route('/'),
        ];
    }
}
