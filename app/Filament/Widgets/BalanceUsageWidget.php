<?php

namespace App\Filament\Widgets;

use App\Models\BalanceRechargeLog;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Symfony\Component\Console\Helper\TreeNode;

class BalanceUsageWidget extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 1;

    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected static ?string $heading = 'Balance Usage';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $datasets = [];
        $labels = [];
        
        switch($activeFilter){
            case 'today':
                $deductedData = Trend::query(Transaction::query()->where('status', 'success'))
                                        ->dateColumn('transaction_time')
                                        ->between(
                                            start: now()->startOfDay(),
                                            end: now()->endOfDay()
                                        )
                                        ->perHour()
                                        ->sum('points_deducted');
                $addedData = Trend::query(BalanceRechargeLog::query())
                                        ->dateColumn('recharge_date')
                                        ->between(
                                            start: now()->startOfDay(),
                                            end: now()->endOfDay()
                                        )
                                        ->perHour()
                                        ->sum('points_added');
                $labels = $deductedData->map(fn(TrendValue $value) => $value->date);
                $datasets = [
                    [
                        'label' => 'Points Deducted',
                        'data' => $deductedData->map(fn(TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(255,99,132)',
                        'background Color' => 'rgba(255,99,132,0.2)',
                    ],
                    [
                        'label' => 'Points Added',
                        'data' => $addedData->map(fn(TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(54,162,235)',
                        'background Color' => 'rgba(54,162,235,0.2)',
                    ]
                ];
                break;
            case 'week':
                $deductedData = Trend::query(Transaction::query()->where('status', 'success'))
                                        ->dateColumn('transaction_time')
                                        ->between(
                                            start: now()->startOfWeek(),
                                            end: now()->endOfWeek()
                                        )
                                        ->perHour()
                                        ->sum('points_deducted');
                $addedData = Trend::query(BalanceRechargeLog::query())
                                        ->dateColumn('recharge_date')
                                        ->between(
                                            start: now()->startOfWeek(),
                                            end: now()->endOfWeek()
                                        )
                                        ->perHour()
                                        ->sum('points_added');
                $labels = $deductedData->map(fn(TrendValue $value) => $value->date);
                $datasets = [
                    [
                        'label' => 'Points Deducted',
                        'data' => $deductedData->map(fn(TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(255,99,132)',
                        'backgroundColor' => 'rgba(255,99,132,0.2)',
                    ],
                    [
                        'label' => 'Points Added',
                        'data' => $addedData->map(fn(TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(54,162,235)',
                        'backgroundColor' => 'rgba(54,162,235,0.2)',
                    ]
                ];
                break;
            
            case 'month':
                $deductedData = Trend::query(Transaction::query()->where('status', 'success'))
                                        ->dateColumn('transaction_time')
                                        ->between(
                                            start: now()->startOfMonth(),
                                            end: now()->endOfMonth()
                                        )
                                        ->perHour()
                                        ->sum('points_deducted');
                $addedData = Trend::query(BalanceRechargeLog::query())
                                        ->dateColumn('recharge_date')
                                        ->between(
                                            start: now()->startOfMonth(),
                                            end: now()->endOfMonth()
                                        )
                                        ->perHour()
                                        ->sum('points_added');
                $labels = $deductedData->map(fn(TrendValue $value) => $value->date);
                $datasets = [
                    [
                        'label' => 'Points Deducted',
                        'data' => $deductedData->map(fn(TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(255,99,132)',
                        'background Color' => 'rgba(255,99,132,0.2)',
                    ],
                    [
                        'label' => 'Points Added',
                        'data' => $addedData->map(fn(TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(54,162,235)',
                        'background Color' => 'rgba(54,162,235,0.2)',
                    ]
                ];
                break;
            case 'year':
                $deductedData = Trend::query(Transaction::query()->where('status', 'success'))
                                        ->dateColumn('transaction_time')
                                        ->between(
                                            start: now()->startOfYear(),
                                            end: now()->endOfYear()
                                        )
                                        ->perHour()
                                        ->sum('points_deducted');
                $addedData = Trend::query(BalanceRechargeLog::query())
                                        ->dateColumn('recharge_date')
                                        ->between(
                                            start: now()->startOfYear(),
                                            end: now()->endOfYear()
                                        )
                                        ->perHour()
                                        ->sum('points_added');
                $labels = $deductedData->map(fn(TrendValue $value) => $value->date);
                $datasets = [
                    [
                        'label' => 'Points Deducted',
                        'data' => $deductedData->map(fn(TrendValue $value) => $value)
                    ],
                    [
                        'label' => 'Points Added',
                        'data' => $addedData->map(fn(TrendValue $value) => $value)
                    ]
                ];
                break;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
