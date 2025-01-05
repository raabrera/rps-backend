<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\GameService;
use App\Http\Requests\CreateNewGameRequest;
use Illuminate\Http\Request;

class GameController extends Controller
{
    protected $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function createGame(CreateNewGameRequest $request)
    {
        $validated = $request->validated();
        $player1 = Player::find($request['player1_id']);
        $player2 = Player::find($request['player2_id']);
        $rounds = $request['rounds'];

        $game = $this->gameService->createGame($player1, $player2, $rounds);
        return response()->json(['game_id' => $game->id]);
    }

    public function playGame($gameId, Request $request)
    {
        $playerMoves = $request->input('player_move');
        
        $gameResults = $this->gameService->playGame($gameId, $playerMoves);

        return response()->json($gameResults);
    }
}
