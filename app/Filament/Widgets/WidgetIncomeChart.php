<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;


class WidgetIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Income Transactions';



    protected function getData(): array
    {
        $data = Trend::query(Transaction::query()->where('type', 'Income'))
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
                    'label' => 'Income Transactions',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'green',
                    'borderColor' => 'green',
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
