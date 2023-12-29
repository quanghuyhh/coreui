<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\RoleEnum;
use App\Models\School;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $schools = School::factory(3)->create();
        foreach (range(1, 10) as $index) {
            $user = User::factory()->create();
            $userRole = UserRole::factory()->create([
                'user_id' => $user->id,
            ]);
            if ($userRole->role !== RoleEnum::HEADQUARTER->value) {
                $userRole->fill([
                    'school_id' => $schools->random()->id
                ])->save();
            }
        }
    }
}
