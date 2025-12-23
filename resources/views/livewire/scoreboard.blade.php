<?php

use Livewire\Volt\Component;
use App\Models\Game;
use App\Models\GameScore;
use App\Models\GameEvent;
use Livewire\Attributes\{Layout, Title};
use Livewire\Attributes\On;


new
    #[Layout('layouts.guest')]
    #[Title('Event Matches')]
    class extends Component {
    public $match;
    public $roundscount;
    public $winnerData = null;

    public $serve;

    public $yellowCards = [];
    public $redCards = [];


    public function mount(Game $match)
    {
        $this->match = $match;
        // $this->winner();
        $this->roundscount = $match->scores()->count();
        $round = GameScore::where('game_id', $match->id)->latest()->first();
        if (!$round) {
            return;
        }
        // $event = GameEvent::where('game_id', $match->id)->where('event_type', 'point')->latest()->first();
        // $this->serve = $event?->player_id ?? null;

        // $this->yellowCards = GameEvent::whereIn('round_id', $match->()->pluck('id'))
        //     ->where('event_type', 'yellow_card')
        //     ->selectRaw('player_id, COUNT(*) as count')
        //     ->groupBy('player_id')
        //     ->pluck('count', 'player_id');

        // $this->redCards = GameEvent::whereIn('round_id', $match->rounds()->pluck('id'))
        //     ->where('event_type', 'red_card')
        //     ->selectRaw('player_id, COUNT(*) as count')
        //     ->groupBy('player_id')
        //     ->pluck('count', 'player_id');
        if ($this->match->status === 'completed') {
            $this->winner();
        }
        // $this->serve = $event?->player_id ?? null;


        // dd($event);
    }



    public function getData()
    {
        // Refresh the match data from the database
        $this->match->refresh();

        // Update total rounds count
        $this->roundscount = $this->match->scores()->count();

        // Get the latest round and point event to determine serve
        $round = GameScore::where('game_id', $this->match->id)->latest()->first();
        $lastEvent = GameEvent::where('game_id', $round->id ?? null)
            ->where('event_type', 'point')
            ->latest()
            ->first();
        $this->serve = $lastEvent?->player_id ?? null;

        // Update yellow and red cards counts
        $roundIds = $this->match->scores()->pluck('id');

        // $this->yellowCards = GameEvent::whereIn('round_id', $roundIds)
        //     ->where('event_type', 'yellow_card')
        //     ->selectRaw('player_id, COUNT(*) as count')
        //     ->groupBy('player_id')
        //     ->pluck('count', 'player_id');

        // $this->redCards = GameEvent::whereIn('round_id', $roundIds)
        //     ->where('event_type', 'red_card')
        //     ->selectRaw('player_id, COUNT(*) as count')
        //     ->groupBy('player_id')
        //     ->pluck('count', 'player_id');
        // // After updating yellow/red cards in getData()
        // if ($this->match->status === 'completed') {
        //     $this->winner();
        // }


        // Optionally, trigger a browser event or toast notification
        // $this->dispatchBrowserEvent('score-updated');
    }

    public function winner()
    {
        $player1Wins = $this->match->player1_rounds_won;
        $player2Wins = $this->match->player2_rounds_won;

        if ($player1Wins > $player2Wins) {
            $this->winnerData = ['name' => $this->match->player1->name];
        } elseif ($player2Wins > $player1Wins) {
            $this->winnerData = ['name' => $this->match->player2->name];
        }
    }






}; ?>

<div wire:poll.1000ms="getData">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #000;
            font-family: 'Arial Black', Arial, sans-serif;
            overflow: hidden;
        }

        .scoreboard-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 2vh 2vw;
            gap: 2vh;
            background: #000;
        }

        .footer {
            background: #fff;
            border: 0.5vh solid #000;
            border-radius: 1.5vh;
            padding: 2vh 3vw;
            height: 12vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-title {
            font-size: 3vh;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.2vw;
            display: flex;
            align-items: center;
            gap: 2vw;
        }

        .footer-logo {
            height: 60px;
            width: auto;
        }

        .scoreboard {
            flex: 1;
            border: 0.5vh solid #000;
            border-radius: 1.5vh;
            display: flex;
            flex-direction: column;
            gap: 2vh;
            background: #000;
            padding: 2vh;
        }

        .teams-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2vh;
        }

        .team-row {
            flex: 1;
            background: linear-gradient(to right, #2a2a2a 0%, #1a1a1a 100%);
            border: 0.4vh solid #444;
            border-radius: 1.5vh;
            display: flex;
            align-items: center;
            padding: 0 3vw;
            box-shadow: 0 0.8vh 2vh rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .team-logo {
            width: 10vh;
            height: 10vh;
            border-radius: 1vh;
            margin-right: 2vw;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 0.3vh solid rgba(255, 255, 255, 0.1);
            font-size: 5vh;
        }

        .logo-team1 {
            background: linear-gradient(135deg, #2196f3 0%, #1565c0 100%);
        }

        .logo-team2 {
            background: linear-gradient(135deg, #ff3d00 0%, #d50000 100%);
        }

        .team-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5vh;
        }

        .team-name {
            color: #ffffff;
            font-size: 10vh;
            font-weight: 900;
            letter-spacing: 0.2vw;
            text-transform: uppercase;
            text-shadow: 0.3vh 0.3vh 0.5vh rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            gap: 2vw;
        }

        .player-names {
            color: #aaa;
            font-size: 2.5vh;
            font-weight: 600;
        }

        .shuttle-indicator {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            animation: bounce 0.6s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-1vh);
            }
        }

        .shuttle-icon {
            font-size: 8vh;
            filter: drop-shadow(0 0 1vh rgba(255, 255, 255, 0.8));
        }

        .cards-container {
            display: flex;
            gap: 1vw;
            align-items: center;
            margin-left: 2vw;
        }

        .card-badge {
            display: flex;
            align-items: center;
            gap: 0.5vw;
            padding: 0.5vh 1vw;
            border-radius: 0.8vh;
            font-size: 3vh;
            font-weight: 900;
            border: 0.3vh solid rgba(0, 0, 0, 0.3);
            box-shadow: 0 0.3vh 0.8vh rgba(0, 0, 0, 0.4);
        }

        .yellow-card-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #000;
        }

        .red-card-badge {
            background: linear-gradient(135deg, #ff1744 0%, #d50000 100%);
            color: #fff;
            animation: pulse-red 1.5s ease-in-out infinite;
        }

        @keyframes pulse-red {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        .card-count {
            font-size: 3.5vh;
            min-width: 3vw;
            text-align: center;
        }

        .scores {
            display: flex;
            gap: 1.5vw;
            align-items: center;
        }

        .round-score {
            background: #790d0d;
            color: #fff;
            border-radius: 1vh;
            font-size: 18vh;
            font-weight: 900;
            min-width: 7vw;
            text-align: center;
            border: 0.3vh solid #333;
            box-shadow: inset 0 0.5vh 1vh rgba(0, 0, 0, 0.8);
            font-family: 'Impact', 'Arial Black', sans-serif;
        }

        .current-score {
            background: linear-gradient(135deg, #d50000 0%, #8b0000 100%);
            color: #fff;
            border-radius: 1vh;
            font-size: 15vh;
            font-weight: 900;
            min-width: 9vw;
            text-align: center;
            border: 0.4vh solid #ff1744;
            box-shadow: 0 0.5vh 2vh rgba(213, 0, 0, 0.6), inset 0 -0.3vh 1vh rgba(0, 0, 0, 0.3);
            font-family: 'Impact', 'Arial Black', sans-serif;
        }

        .wins-indicator {
            background: linear-gradient(135deg, #ffd700 0%, #ffa000 100%);
            color: #000;
            padding: 1vh 1vw;
            border-radius: 1vh;
            font-size: 6vh;
            font-weight: 900;
            min-width: 6vw;
            text-align: center;
            border: 0.3vh solid #ffeb3b;
            box-shadow: 0 0.5vh 1.5vh rgba(255, 215, 0, 0.4);
        }

        .controls {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 1vh;
            margin-top: 2vh;
            padding-top: 2vh;
            border-top: 0.2vh solid #444;
        }

        button {
            background: rgba(213, 0, 0, 0.6);
            border: 0.3vh solid #ff1744;
            color: #fff;
            padding: 1.5vh 1vw;
            font-size: 1.8vh;
            border-radius: 0.8vh;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
            font-family: 'Arial Black', Arial, sans-serif;
        }

        button:hover {
            background: rgba(213, 0, 0, 0.8);
            transform: scale(1.05);
        }

        button:active {
            transform: scale(0.95);
        }

        .control-section {
            display: flex;
            flex-direction: column;
            gap: 1vh;
        }

        .control-label {
            color: #fff;
            font-size: 1.5vh;
            font-weight: bold;
            text-align: center;
        }
    </style>

    <div class="scoreboard-container">
        <div class="footer">
            <div class="footer-title">
                <span>🏸</span>
                <span>Badminton - Court {{ $match->court_number }}</span>
            </div>
            <div class="footer-title">
                Tournament 2025
            </div>
        </div>

        <div class="scoreboard">
            <div class="teams-container">
                <!-- Team 1 -->
                <div class="team-row">
                    <div class="team-logo logo-team1">🏸</div>

                    <div class="team-info">
                        <div class="team-name">
                            <span id="team1Name">{{ $match->team1->name }}</span>
                            <span id="shuttle1" class="shuttle-indicator"
                                {{-- style="{{ $match->player1->id == $serve ? '' : 'display: none;' }}" --}}
                                >
                                <span class="shuttle-icon">🏸</span>
                            </span>
                            <span id="team1Cards" class="cards-container">
                                {{-- @if(($yellowCards[$match->player1->id] ?? 0) > 0)
                                    <span class="card-badge yellow-card-badge">
                                        🟨 <span class="card-count">{{ $yellowCards[$match->player1->id] }}</span>
                                    </span>
                                @endif --}}
                                {{-- @if(($redCards[$match->player1->id] ?? 0) > 0)
                                    <span class="card-badge red-card-badge">
                                        🟥 <span class="card-count">{{ $redCards[$match->player1->id] }}</span>
                                    </span>
                                @endif --}}
                            </span>
                        </div>
                        {{-- <div class="player-names" id="team1Players">{{ $match->->subtext }}</div> --}}
                    </div>

                    <div class="scores">
                        <div class="wins-indicator" id="team1Wins">{{ $match->team1_points ?? 0 }}</div>
                        @foreach ($match->scores as $round)

                            @if ($loop->last)
                                <div class="current-score" id="team1Score">
                                    {{ str_pad($round->team1_score, 2, '0', STR_PAD_LEFT) }}</div>
                            @else
                                <div class="round-score" id="team1R2">{{ str_pad($round->team1_score, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            @endif


                        @endforeach
                    </div>
                </div>

                <!-- Team 2 -->
                <div class="team-row">
                    <div class="team-logo logo-team2">🏸</div>

                    <div class="team-info">
                        <div class="team-name">
                            <span id="team2Name">{{ $match->team2->name }}</span>
                            <span id="shuttle2" class="shuttle-indicator"
                                {{-- style="{{ $match->player2->id == $serve ? '' : 'display: none;' }}" --}}
                        >
                                <span class="shuttle-icon">🏸</span>
                            </span>
                            <span id="team1Cards" class="cards-container">
                                {{-- @if(($yellowCards[$match->player2->id] ?? 0) > 0)
                                    <span class="card-badge yellow-card-badge">
                                        🟨 <span class="card-count">{{ $yellowCards[$match->player2->id] }}</span>
                                    </span>
                                @endif
                                @if(($redCards[$match->player2->id] ?? 0) > 0)
                                    <span class="card-badge red-card-badge">
                                        🟥 <span class="card-count">{{ $redCards[$match->player2->id] }}</span>
                                    </span>
                                @endif --}}
                            </span>
                        </div>
                        {{-- <div class="player-names" id="team2Players">{{ $match->player2->team }}</div> --}}
                    </div>

                    <div class="scores">
                        <div class="wins-indicator" id="team2Wins">{{ $match->team2_points ?? 0 }}</div>
                        @foreach ($match->scores as $round)
                           @if ($loop->last)
                                <div class="current-score" id="team2Score">{{ str_pad($round->team2_score, 2, '0', STR_PAD_LEFT) }}</div>
                            @else
                                <div class="round-score" id="team2R2">{{ str_pad($round->team2_score, 2, '0', STR_PAD_LEFT) }}</div>
                            @endif


                        @endforeach

                    </div>
                </div>
            </div>
        </div>


    </div>
    @if($winnerData)
        <div style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.9);
                color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                text-align: center;
                animation: fadeIn 0.8s ease-in-out;">
            <h1 style="font-size: 12vh; font-weight: 900; margin-bottom: 2vh; text-transform: uppercase;">
                🏆 Winner 🏆
            </h1>
            <h2 style="font-size: 8vh; color: gold; font-weight: bold;">
                {{ $winnerData['name'] ?? 'Unknown' }}
            </h2>
            <p style="font-size: 4vh; color: #aaa;">Congratulations!</p>
        </div>

        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
        </style>
    @endif



</div>