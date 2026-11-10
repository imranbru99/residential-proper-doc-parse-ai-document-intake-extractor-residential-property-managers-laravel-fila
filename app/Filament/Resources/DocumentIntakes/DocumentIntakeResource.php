<?php

namespace App\Filament\Resources\DocumentIntakes;

use App\Filament\Resources\DocumentIntakes\Pages\ManageDocumentIntakes;
use App\Models\DocumentIntake;
use App\Services\DocumentExtractionService;
use App\Support\ModuleManager;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class DocumentIntakeResource extends Resource
{
    protected static ?string $model = DocumentIntake::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Document Operations';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleManager::enabled('document_intake');
    }

    public static function canAccess(): bool
    {
        return ModuleManager::enabled('document_intake');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('reference')->required()->maxLength(50),
            Select::make('source')->options([
                'upload' => 'Upload',
                'email' => 'Email',
                'integration' => 'Integration',
                'portal' => 'Resident Portal',
            ])->required(),
            TextInput::make('original_filename')->required()->maxLength(255),
            Select::make('document_type')->options([
                'resident_form' => 'Resident Form',
                'lease_packet' => 'Lease Packet',
                'maintenance_photo' => 'Maintenance Photo',
                'vendor_invoice' => 'Vendor Invoice',
                'move_in_checklist' => 'Move-in Checklist',
            ])->required(),
            Select::make('property_id')->relationship('property', 'name')->searchable()->preload(),
            Select::make('status')->options([
                'received' => 'Received',
                'extracting' => 'Extracting',
                'pending_approval' => 'Pending Approval',
                'exception' => 'Exception',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
            ])->required(),
            TextInput::make('confidence_score')->numeric(),
            Textarea::make('exception_reason')->columnSpanFull(),
            TextInput::make('routed_queue'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable()->sortable(),
                TextColumn::make('original_filename')->limit(30)->searchable(),
                TextColumn::make('source')->badge(),
                TextColumn::make('document_type')->badge(),
                TextColumn::make('property.name')->label('Property')->toggleable(),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'approved' => 'success',
                    'exception' => 'danger',
                    'pending_approval' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('confidence_score')->suffix('%'),
                TextColumn::make('routed_queue')->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('extract')
                    ->label('AI Extract')
                    ->icon('heroicon-o-sparkles')
                    ->visible(fn (DocumentIntake $record) => ModuleManager::enabled('ai_field_extraction') && $record->status === 'received')
                    ->action(function (DocumentIntake $record) {
                        app(DocumentExtractionService::class)->process($record);
                        Notification::make()->title('AI extraction complete')->success()->send();
                    }),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (DocumentIntake $record) => ModuleManager::enabled('human_approval') && in_array($record->status, ['pending_approval', 'exception'], true))
                    ->action(function (DocumentIntake $record) {
                        app(DocumentExtractionService::class)->approve($record, auth()->id());
                        Notification::make()->title('Document approved & routed')->success()->send();
                    }),
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
            'index' => ManageDocumentIntakes::route('/'),
        ];
    }
}
