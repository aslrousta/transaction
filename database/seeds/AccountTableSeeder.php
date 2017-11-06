<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        for ($i = 1; $i <= 1000; $i++) {
            DB::table('account')->insert([
                'id'         => $i,
                'balance'    => 1000000,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
