<?php

namespace App\Filament\Widgets;

use App\Models\Withdrawal;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardChart2 extends LineChartWidget
{
    protected static ?string $heading = 'Withdrawals';
    public ?string $filter = 'today';
    protected static ?string $pollingInterval = null;
    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = Trend::model(Withdrawal::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Latest Withdrawal',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
}
