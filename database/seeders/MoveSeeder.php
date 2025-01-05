<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Move;

class MoveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Move::create(['name' => 'rock','rules' => json_encode(['beats' => ['scissors', 'lizard']])]);
        Move::create(['name' => 'paper', 'rules' => json_encode(['beats' => ['rock', 'spock']])]);
        Move::create(['name' => 'scissors', 'rules' => json_encode(['beats' => ['paper', 'lizard']])]);
    }
}
