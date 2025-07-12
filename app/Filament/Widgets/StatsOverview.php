<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();
            
        $pemasukan = Transaction::where('type', 'income')->whereBetween('date', [$startDate, $endDate])->sum('amout');
        $pengeluaran = Transaction::where('type', 'expense')->whereBetween('date', [$startDate, $endDate])->sum('amout');
        $profit = $pemasukan - $pengeluaran;

        return [
            Stat::make('Income', 'Rp ' . number_format($pemasukan, 0, ',', '.'))
                ->description('↑ Total Income ')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Expense', 'Rp ' . number_format($pengeluaran, 0, ',', '.'))
                ->description('↑ Total Expense ')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Profit', 'Rp ' . number_format($profit, 0, ',', '.'))
                ->description('↑ Total Profit ')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($profit >= 0 ? 'success' : 'danger'),
        ];
    }
}
