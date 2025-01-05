<?php 

namespace App\Services;

use App\Models\Game;
use App\Models\GameResult;
use App\Models\Move;
use App\Models\Player;

class GameService
{
    public function getRandomMove()
    {
        return Move::inRandomOrder()->first()->name;
    }

    public function getPlayerMove(Player $player)
    {
        // Return manual or automatic move based on player type
        return $player->type === 'automatic' ? $this->getRandomMove() : null;
    }

    public function createGame(Player $player1, Player $player2, $rounds)
    {
        return Game::create([
            'player1_id' => $player1->id,
            'player2_id' => $player2->id,
            'rounds' => $rounds,
            'currentRound' => 1
        ]);
    }

    public function playGame($gameId, $player2Move)
    {
        $game = Game::findOrFail($gameId);
        if($game->currentRound > $game->rounds){
            return 'game already completed';
        }
        // Generate a random move for Player 1
        $player1Move = $this->getRandomMove();
        $winner = $this->determineWinner($player1Move, $player2Move);
        $gameResult = GameResult::create([
            'game_id' => $game->id,
            'player1_move' => $player1Move,
            'player2_move' => $player2Move,
            'winner' => $winner,
        ]);
        // Check if the game has reached the total number of rounds
        $gameCompleted = $game->currentRound >= $game->rounds;
        
        // Update the game state (increment round count)
        $game->increment('currentRound');


        $gameSummary = $this->getGameSummary($gameId);
        // Return the round result along with the updated game state
        return [
            'game_result' => $gameResult,
            'game_summary' => $gameSummary,
            'game_completed' => $gameCompleted,
            'player1_move' => $player1Move,
            'player2_move' => $player2Move,
            'winner' => $winner,
            'current_round' => $game->currentRound,
            'total_rounds' => $game->rounds,
        ];
    }

    public function determineWinner($move1, $move2)
    {
        // Retrieve moves from the database
        $move1_data = Move::where('name', $move1)->first();
        $move2_data = Move::where('name', $move2)->first();

        if ($move1_data && $move2_data) {
            // Decode the JSON rules
            $move1_rules = json_decode($move1_data->rules, true);
            $move2_rules = json_decode($move2_data->rules, true);

            // Check for a tie
            if ($move1 === $move2) {
                return 'tie';
            }

            // Check if move1 beats move2
            if (in_array($move2, $move1_rules['beats'])) {
                return 'player1';
            }

            // Check if move2 beats move1
            if (in_array($move1, $move2_rules['beats'])) {
                return 'player2';
            }
        }

        return 'tie'; // Default to tie if no winner is determined
    }
    public function getGameSummary($gameId)
    {
        // Fetch the game instance
        $game = Game::findOrFail($gameId);

        // Fetch all results for the game
        $results = GameResult::where('game_id', $gameId)->get();

        // Initialize counters
        $player1Wins = 0;
        $player2Wins = 0;
        $ties = 0;

        // Count the outcomes
        foreach ($results as $result) {
            if ($result->winner === 'player1') {
                $player1Wins++;
            } elseif ($result->winner === 'player2') {
                $player2Wins++;
            } else {
                $ties++;
            }
        }

        $totalRounds = $results->count();

        // Calculate win percentages
        $player1WinPercentage = $totalRounds > 0 ? ($player1Wins / $totalRounds) * 100 : 0;
        $player2WinPercentage = $totalRounds > 0 ? ($player2Wins / $totalRounds) * 100 : 0;

        // Return the summary
        return [
            'status' => 'completed',
            'game_id' => $gameId,
            'total_rounds' => $totalRounds,
            'player1_wins' => $player1Wins,
            'player2_wins' => $player2Wins,
            'ties' => $ties,
            'player1_win_percentage' => round($player1WinPercentage, 2),
            'player2_win_percentage' => round($player2WinPercentage, 2),
        ];
    }
}



