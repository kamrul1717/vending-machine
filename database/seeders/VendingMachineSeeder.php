<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class VendingMachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. System Settings
        DB::table('system_settings')->insert([
            ['key' => 'currency', 'value' => 'Points', 'description' => 'Currency used for vending purchases', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'max_daily_limit', 'value' => '5', 'description' => 'Max purchases per employee per day', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 2. Product Categories
        DB::table('product_categories')->insert([
            ['name' => 'Snacks', 'slug' => 'snacks', 'description' => 'Chips, biscuits, etc.', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Drinks', 'slug' => 'drinks', 'description' => 'Soft drinks, juice, water', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. Classifications
        DB::table('classifications')->insert([
            ['name' => 'Regular', 'daily_point_limit' => 100, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Premium', 'daily_point_limit' => 200, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4. Classification Limits
        DB::table('classification_limits')->insert([
            ['classification_id' => 1, 'product_category_id' => 1, 'daily_limit' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['classification_id' => 1, 'product_category_id' => 2, 'daily_limit' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['classification_id' => 2, 'product_category_id' => 1, 'daily_limit' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['classification_id' => 2, 'product_category_id' => 2, 'daily_limit' => 4, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 5. Employees
        DB::table('employees')->insert([
            ['full_name' => 'John Doe', 'email' => 'john@example.com', 'employee_code' => 'EMP001', 'classification_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['full_name' => 'Jane Smith', 'email' => 'jane@example.com', 'employee_code' => 'EMP002', 'classification_id' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 6. Employee Balances
        DB::table('employee_balances')->insert([
            ['employee_id' => 1, 'current_points' => 80, 'last_recharge_date' => $now, 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 2, 'current_points' => 150, 'last_recharge_date' => $now, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 7. Balance Recharge Logs
        DB::table('balance_recharge_logs')->insert([
            ['employee_id' => 1, 'recharge_date' => $now, 'points_added' => 50, 'previous_balance' => 30, 'new_balance' => 80, 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 2, 'recharge_date' => $now, 'points_added' => 100, 'previous_balance' => 50, 'new_balance' => 150, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 8. Machines
        DB::table('machines')->insert([
            ['name' => 'Machine A', 'location' => 'Lobby', 'ip_address' => '192.168.0.10', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Machine B', 'location' => 'Cafeteria', 'ip_address' => '192.168.0.11', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 9. Slots
        DB::table('slots')->insert([
            ['machine_id' => 1, 'product_category_id' => 1, 'slot_number' => 1, 'price' => 10, 'product_name' => 'Lays Chips', 'is_available' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['machine_id' => 1, 'product_category_id' => 2, 'slot_number' => 2, 'price' => 15, 'product_name' => 'Coca Cola', 'is_available' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['machine_id' => 2, 'product_category_id' => 1, 'slot_number' => 1, 'price' => 12, 'product_name' => 'Oreo Cookies', 'is_available' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 10. Cards
        DB::table('cards')->insert([
            ['card_number' => 'CARD1001', 'employee_id' => 1, 'is_active' => 1, 'issued_date' => $now, 'expired_date' => $now->copy()->addYear(), 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
            ['card_number' => 'CARD1002', 'employee_id' => 2, 'is_active' => 1, 'issued_date' => $now, 'expired_date' => $now->copy()->addYear(), 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 11. Employee Daily Product Limits
        DB::table('employee_daily_product_limits')->insert([
            ['employee_id' => 1, 'product_category_id' => 1, 'count_date' => $now, 'daily_count' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 2, 'product_category_id' => 2, 'count_date' => $now, 'daily_count' => 0, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 12. Transactions
        DB::table('transactions')->insert([
            [
                'employee_id' => 1,
                'card_id' => 1,
                'machine_id' => 1,
                'slot_id' => 1,
                'product_category_id' => 1,
                'points_deducted' => 10,
                'transaction_time' => $now,
                'status' => 'completed',
                'failure_reason' => null,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'employee_id' => 2,
                'card_id' => 2,
                'machine_id' => 2,
                'slot_id' => 3,
                'product_category_id' => 1,
                'points_deducted' => 12,
                'transaction_time' => $now,
                'status' => 'completed',
                'failure_reason' => null,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
