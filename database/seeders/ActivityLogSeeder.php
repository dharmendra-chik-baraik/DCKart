<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create 0-10 activity logs per user
            $logCount = rand(0, 10);
            
            ActivityLog::factory($logCount)->create([
                'user_id' => $user->id,
            ]);
        }

        echo "Activity logs seeded: " . ActivityLog::count() . "\n";
    }
}