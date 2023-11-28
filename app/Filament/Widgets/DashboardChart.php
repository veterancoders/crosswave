<?php

namespace App\Filament\Widgets;

use App\Models\Deposit;
use App\Models\Investment;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardChart extends LineChartWidget
{
    protected static ?string $heading = 'Deposits';

    protected function getData(): array
    {
        $data = Trend::model(Deposit::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Top Deposits',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
