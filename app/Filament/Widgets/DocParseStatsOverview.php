<?php

namespace App\Filament\Widgets;

use App\Models\Approval;
use App\Models\DocumentIntake;
use App\Models\MaintenanceRequest;
use App\Models\VendorWorkOrder;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DocParseStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Document Intakes', DocumentIntake::query()->count())
                ->description('Lease, resident & maintenance PDFs')
                ->icon('heroicon-o-document-text'),
            Stat::make('Pending Approvals', Approval::query()->where('status', 'pending')->count())
                ->description('Awaiting human review')
                ->color('warning')
                ->icon('heroicon-o-check-badge'),
            Stat::make('Maintenance Requests', MaintenanceRequest::query()->count())
                ->description('Created from approved intake')
                ->icon('heroicon-o-wrench-screwdriver'),
            Stat::make('Vendor Work Orders', VendorWorkOrder::query()->count())
                ->description('Ready for vendor dispatch')
                ->icon('heroicon-o-clipboard-document-check'),
        ];
    }
}
