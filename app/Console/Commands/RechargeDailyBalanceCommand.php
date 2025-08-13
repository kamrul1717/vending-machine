<?php

namespace App\Console\Commands;

use App\Models\BalanceRechargeLog;
use App\Models\Employee;
use App\Models\EmployeeBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RechargeDailyBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:recharge-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recharge daily balance for all employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Starting daily balance recharge...');

        return DB::transaction(function(){

            $employees = Employee::with(['classification', 'employeeBalance'])->get();
            $rechargeCount = 0;
            
            if($employees){
                foreach($employees as $employee){
                    $balance = $employee->employeeBalance;
                    if(!$balance){
                        $balance = new EmployeeBalance([
                            'employee_id' => $employee->id,
                            'current_points' => 0,
                        ]);
                    }

                    $previousBalance = $balance->current_points;

                    $balance->current_points = 0;

                    $pointsToAdd = $this->getPointsForClassification($employee->classification->name);
                    $balance->current_points += $pointsToAdd;
                    $balance->last_recharge_date = now();
                    $balance->save();

                    BalanceRechargeLog::create([
                        'employee_id' => $employee->id,
                        'recharge_date' => now(),
                        'points_added' => $pointsToAdd,
                        'previous_balance' => $previousBalance,
                        'new_balance' => $balance->current_points,
                    ]);

                    $rechargeCount++;
                    
                }

                $this->components->info("Successfully recharged balance for {$rechargeCount}");

                return self::SUCCESS;
            }
            
        }, 5);
    }

    private function getPointsForClassification(string $classification){
        return match(strtolower($classification)){
            'manager' => 500,
            'default' => 300,
        };
    }

}
