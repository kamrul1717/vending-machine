<?php

namespace App\Http\Controllers;

use App\Exceptions\DailyQuotaExceededException;
use App\Exceptions\EmployeeBalanceNotFoundException;
use App\Exceptions\EmployeeNotFoundException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidCardException;
use App\Exceptions\InvalidSlotException;
use App\Exceptions\ProductCategoryNotAllowedException;
use App\Exceptions\ProductPriceMismatchException;
use App\Http\Requests\PurchaseRequest;
use App\Models\Card;
use App\Models\ClassificationLimit;
use App\Models\Employee;
use App\Models\EmployeeBalance;
use App\Models\EmployeeDailyProductLimit;
use App\Models\Slot;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB;
use PHPUnit\Framework\MockObject\Generator\ClassIsFinalException;

class PurchaseController extends Controller
{
    public function process(PurchaseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $transactionTime = $data['timestamp'] ?? now();

        return DB::transaction(function () use ($data, $transactionTime) {
            $card = Card::where('card_number', $data['card_number'])
                    ->where('is_active', true)
                    ->where(function($query) {
                        $query->whereNull('expired_date')
                              ->orWhere('expired_date', '>=', now());
                    })
                    ->first();
            if(!$card) {
                throw new InvalidCardException();
            }

            $employee = Employee::find($card->employee_id);
            if(!$employee){
                throw new EmployeeNotFoundException();
            }

            $slot = Slot::where('machine_id', $data['machine_id'])
                          ->where('slot_number', $data['slot_number'])
                          ->where('is_available', true)
                          ->whereHas('machine', function($query){
                            $query->where('is_active', true);
                          })
                          ->first();

            if(!$slot){
                throw new InvalidSlotException();
            }

            if($slot->price != $data['product_price']){
                throw new ProductPriceMismatchException();
            }

            $classificationLimit = ClassificationLimit::where('classification_id', $employee->classification_id)
                                                        ->where('product_category_id', $slot->product_category_id)
                                                        ->first();

            if(!$classificationLimit){
                throw new ProductCategoryNotAllowedException();
            }

            $balance = EmployeeBalance::where('where', $employee->id)->first();
            if(!$balance){
                throw new EmployeeBalanceNotFoundException();
            }

            if(!$balance->current_points < $data['product_price']){
                throw new InsufficientBalanceException();
            }

            $today = Carbon::parse($transactionTime)->toDateString();
            $dailyCount = EmployeeDailyProductLimit::firstOrNew([
                'employee_id' => $employee->id,
                'product_category_id' => $slot->product_category_id,
                'count_date' => $today,
            ]);

            if(!$dailyCount->exists){
                $dailyCount->daily_count = 0;
                $dailyCount->save();
            }

            if($dailyCount->daily_count >= $classificationLimit->daily_limit){
                throw new DailyQuotaExceededException();
            }

            $balance->current_points -= $data['product_price'];
            $balance->save();

            $dailyCount->daily_count += 1;
            $dailyCount->save();

            $transaction = Transaction::create([
                'employee_id' => $employee->id,
                'card_id' => $card->id,
                'machine_id' => $data['machine_id'],
                'slot_id' => $slot->id,
                'product_category_id' => $slot->product_category_id,
                'points_deducted' => $data['product_price'],
                'transaction_time' => $transactionTime,
                'status' => 'success',
            ]);

            $result = [
                'transaction_id' => $transaction->id,
                'points_deducted' => $data['product_price'],
                'remaining_balance' => $balance->current_points,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Purchase Successful',
                'data' => $result,
            ]);

        });
    }
}
