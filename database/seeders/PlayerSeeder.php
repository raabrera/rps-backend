<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;
class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Player::create(['name' => 'Player 1', 'type' => 'manual']);
        Player::create(['name' => 'Player 2', 'type' => 'automatic']);
    }
}
