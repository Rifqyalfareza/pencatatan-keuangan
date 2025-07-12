<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Flowframe\Trend\Trend;
use App\Models\Transaction;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\ChartWidget;

class WidgetExpanseChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Monthly Expanse Transactions';

    protected function getData(): array
    {
        // $startDate = ! is_null($this->filters['startDate'] ?? null) ?
        //     Carbon::parse($this->filters['startDate']) :
        //     null;

        // $endDate = ! is_null($this->filters['endDate'] ?? null) ?
        //     Carbon::parse($this->filters['endDate']) :
        //     now();
            
        $dataExpanse = Trend::query(Transaction::query()->where('type', 'Expense'))
            ->dateColumn('date')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('amout');

        return [
            'datasets' => [
            [
                'label' => 'Expanse Transactions',
                'data' => $dataExpanse->map(fn(TrendValue $value) => $value->aggregate),
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ],
        ],
        'labels' => $dataExpanse->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
