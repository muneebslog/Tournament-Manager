<?php

use Livewire\Volt\Component;
use App\Models\GameMatch;
use App\Models\Event;
use App\Models\Player;
use Carbon\Carbon;
use App\Events\MatchUpdated;



new class extends Component {
    public $match;
    public $showcontrolpanel = false;
    public $modal;
    public $round;
    public $sets;
    public $roundWinner;
    public $startButton;
    public $winnerName;
    public $manualEventPlayer;
    public $manualEventType;



    public function mount(GameMatch $match)
    {
        $this->match = $match;
        $this->sets = $this->match->max_rounds;
        $this->round = $this->match->rounds()->latest()->first();
        if ($this->round == null) {
            $this->modal = true;
            $this->startButton = true;
        } else {
            $this->showcontrolpanel = true;
        }
        if ($this->match->status == 'completed') {
            $this->showcontrolpanel = false;
            $this->modal = true;
            $this->roundWinner = $this->match->winner_id;
        }
    }

    public function StartRound()
    {
        $this->match->status = "ongoing";
        $this->match->shuttles_used = 1;
        $this->match->save();
        $this->round = $this->match->rounds()->create([
            'status' => 'ongoing',
            'started_at' => now(),
            'round_number' => ($this->match->rounds()->count() + 1),
            'player1_score' => 0,
            'player2_score' => 0,
        ]);
        $this->showcontrolpanel = true;
        $this->modal = false;
        $this->logEvent('start_round', null, 'Round ' . $this->round->round_number . ' started');

    }

    public function increaseScore($player)
    {
        if ($player == 'player1') {
            $this->round->player1_score += 1;
        } elseif ($player == 'player2') {
            $this->round->player2_score += 1;
        }
        $this->round->save();
        $id = $this->match->{$player}->id;
        $name = $this->match->{$player}->name;
        $this->logEvent('point', $id, 'Player ' . $name . ' scored +1');
        $this->WinnerCheck();

        // Example inside increaseScore() after saving

    }

    public function decreaseScore($player)
    {
        if ($player == 'player1' && $this->round->player1_score > 0) {
            $this->round->player1_score -= 1;
        } elseif ($player == 'player2' && $this->round->player2_score > 0) {
            $this->round->player2_score -= 1;
        }
        $this->round->save();
        $id = $this->match->{$player}->id;
        $name = $this->match->{$player}->name;

        $this->logEvent('point_deduction', $id, 'Player ' . $name . ' scored -1');
    }

    public function WinnerCheck()
    {
        $winningScore = 21;
        $scoreDifference = 2;
        $maxScore = 30; // optional, use if you want a hard limit (e.g., badminton rule)

        $p1 = $this->round->player1_score;
        $p2 = $this->round->player2_score;

        // Check if either player has reached at least the winning score
        if ($p1 >= $winningScore || $p2 >= $winningScore) {
            $scoreDiff = abs($p1 - $p2);

            // Handle deuce logic: must lead by at least 2 OR reach max score
            if ($scoreDiff >= $scoreDifference || $p1 == $maxScore || $p2 == $maxScore) {
                // We have a winner
                $winner_id = $p1 > $p2 ? $this->match->player1_id : $this->match->player2_id;
                $this->EndRound($winner_id);
                $this->logEvent('winner', $winner_id, 'Round ' . $this->round->round_number . ' won by ' . $this->winnerName);
                $this->showcontrolpanel = false;
                $this->modal = true;
                $this->roundWinner = $winner_id;
                $this->winnerName = $winner_id == $this->match->player1_id ? $this->match->player1->name : $this->match->player2->name;

            }
        }
    }

    public function EndRound($winner_id)
    {

        // Mark current round as completed
        $this->round->update([
            'status' => 'completed',
            'winner_id' => $winner_id,
            'ended_at' => now(),
        ]);
        $this->logEvent('end_round', null, 'Round ' . $this->round->round_number . ' ended');

        // Count total rounds per player
        $player1Rounds = $this->match->rounds()->where('winner_id', $this->match->player1_id)->count();
        $player2Rounds = $this->match->rounds()->where('winner_id', $this->match->player2_id)->count();

        // Update rounds won in the match table
        $this->match->update([
            'player1_rounds_won' => $player1Rounds,
            'player2_rounds_won' => $player2Rounds,
        ]);

        // Get last two winners to check for consecutive wins
        $lastTwoWinners = $this->match->rounds()
            ->orderByDesc('round_number')
            ->take(2)
            ->pluck('winner_id')
            ->toArray();

        $hasTwoConsecutiveWins = count($lastTwoWinners) === 2 && $lastTwoWinners[0] === $lastTwoWinners[1];

        // Check if this was the final round
        $isLastSet = $this->round->round_number >= $this->sets;

        // Decide match outcome
        if ($hasTwoConsecutiveWins || $isLastSet) {
            $this->match->update([
                'status' => 'completed',
                'winner_id' => $winner_id,
            ]);
            


            $this->modal = true;
            $this->startButton = false;

        } else {
            // Continue to next round
            $this->modal = true;
            $this->startButton = true;
        }
    }

    public function logEvent($eventType, $playerId, $description)
    {
        Event::create([
            'round_id' => $this->round->id,
            'player_id' => $playerId,
            'event_type' => $eventType,
            'description' => $description,
            'player1_score' => $this->round->player1_score,
            'player2_score' => $this->round->player2_score,
            'timestamp' => now(),
        ]);
    }

    public function getEventsProperty()
    {
        return $this->round
            ? $this->round->events()->latest()->take(10)->get()
            : collect();
    }

    public function shuttlesUsed($change)
    {
        if ($change == 'increase') {
            $this->match->shuttles_used += 1;
        } elseif ($change == 'decrease' && $this->match->shuttles_used > 0) {
            $this->match->shuttles_used -= 1;
        }
        $this->match->save();
        $this->logEvent('shuttle_change', null, 'Shuttles used updated to ' . $this->match->shuttles_used);

    }

    public function logManualEvent()
    {
        // dd($this->manualEventPlayer , $this->manualEventType);
        $val = $this->validate([
            'manualEventType' => 'required',
            'manualEventPlayer' => 'required|numeric'
        ]);
        // dd($val);
        $player = Player::find($this->manualEventPlayer);
        if ($this->manualEventType == 'red_card') {
            $desc = "Player" . $player->name . 'has Gotten Red Card 🟥';
        } elseif ($this->manualEventType == 'yellow_card') {
            $desc = "Player" . $player->name . 'has Gotten Yellow Card 🟨';
        } elseif ($this->manualEventType == 'injury') {
            $desc = "Player" . $player->name . 'has Gotten Injured';
        }
        $this->logEvent($this->manualEventType, $this->manualEventPlayer, $desc);
    }





}; ?>

<div>
    <!-- Header -->
    <header
        class="bg-gradient-to-r rounded from-blue-600 to-indigo-600 text-white shadow-lg dark:from-blue-900 dark:to-indigo-900">
        <div class="mx-auto max-w-7xl px-6 py-2">
            <!-- Top Row: Serial & Theme Toggle -->

            <!-- Main Header Content -->
            <div class="grid grid-cols-4 items-center gap-4">
                <!-- Left: Court Number -->
                <div class="text-left">
                    <p class="text-sm opacity-75">Court</p>
                    <p class="text-3xl font-bold"># {{ $match->court_number }}</p>
                </div>

                <!-- Center: Title -->
                <div class="text-center col-span-2">
                    <h1 class="text-4xl font-bold">{{ $match->title }}</h1>
                    <span class="text-sm font-semibold opacity-90">Sn# {{ $match->match_serial_number }}</span>
                </div>

                <!-- Right: Round Number -->
                <div class="text-right">
                    <p class="text-sm opacity-75">Round</p>
                    <p class="text-3xl font-bold" id="roundDisplay">{{ $round->round_number ?? '0' }}</p>
                </div>
            </div>
        </div>
    </header>

    @if ($showcontrolpanel)


        <div class="grid grid-cols-2 gap-1">
            <!-- Ahmad Card -->
            {{-- <div class="flex border absolute left-1/2 rounded p-1  top-1/2 -translate-y-1/2 ">
                <flux:icon.arrow-left variant="micro" />
                <flux:icon.arrow-right variant="micro" />
            </div> --}}
            <div
                class="bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 rounded-lg px-8 py-6 shadow-lg">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $match->player1->name }}</h2>
                </div>

                <div class="mb-8 text-center">
                    <p class="text-5xl font-bold text-blue-600 dark:text-blue-400">{{ $round->player1_score }}</p>
                </div>

                <div class="flex justify-center gap-6">
                    <flux:button variant="primary" color="red" wire:click="decreaseScore('player1')" class="">
                        <span class="px-2 text-4xl">-</span>

                    </flux:button>
                    <flux:button variant="primary" color="green" wire:click="increaseScore('player1')" class="">
                        <span class="px-2 text-4xl">+</span>
                    </flux:button>
                </div>
            </div>

            <!-- Usman Card -->
            <div
                class="bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 rounded-lg px-8 py-6 shadow-lg">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $match->player2->name }}</h2>
                </div>

                <div class="mb-8 text-center">
                    <p class="text-5xl font-bold text-purple-600 dark:text-purple-400">{{ $round->player2_score }}</p>
                </div>

                <div class="flex justify-center gap-6">
                    <flux:button variant="primary" color="red" wire:click="decreaseScore('player2')" class="">
                        <span class="px-2 text-4xl">-</span>
                    </flux:button>
                    <flux:button variant="primary" color="green" wire:click="increaseScore('player2')" class="">
                        <span class="px-2 text-4xl">+</span>
                    </flux:button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 grid-rows-1 gap-1 sm:mt-2">
            <div
                class="bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 rounded-lg p-6 sm:mb-8 shadow-lg">

                <!-- Activity Log -->
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Activity Log</h3>

                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($this->events as $event)
                        <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-gray-500 dark:text-gray-400 text-sm font-semibold">
                                {{ Carbon::parse($event->timestamp)->diffForHumans() }}
                            </span>
                            <div class="flex-1">
                                <p class="text-gray-700 dark:text-gray-200 font-medium">
                                    {{ $event->description }}
                                </p>
                            </div>
                            <span
                                class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-semibold capitalize">
                                {{ str_replace('_', ' ', $event->event_type) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 rounded-lg p-6 mb-8 shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Log Event</h3>
                <div class="space-y-4">

                    <flux:radio.group wire:model="manualEventType" variant="segmented">
                        <flux:radio label="Injury" value="injury" icon="eye" />
                        <flux:radio label="Red Card" value="red_card" icon="wrench" />
                        <flux:radio label="Yellow Card" value="yellow_card" icon="pencil-square" />
                    </flux:radio.group>
                    <flux:select wire:model="manualEventPlayer" class=" flex">
                        <flux:select.option>Choose Player</flux:select.option>
                        <flux:select.option value="{{ $match->player1->id }}">{{ $match->player1->name }}
                        </flux:select.option>
                        <flux:select.option value="{{ $match->player2->id }}">{{ $match->player2->name }}
                        </flux:select.option>
                    </flux:select>
                    <flux:button wire:click="logManualEvent" class=" w-full">Log</flux:button>

                </div>
                <div class="flex mt-4 justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-2">Shuttles:</h3>
                    <flux:button wire:click="shuttlesUsed('decrease')" variant="primary" size="sm" color="red">
                        <span class="text-xl">-</span>
                    </flux:button>
                    <span>{{ $match->shuttles_used }}</span>
                    <flux:button wire:click="shuttlesUsed('increase')" variant="primary" size="sm" color="green">
                        <span class="text-xl">+</span>
                    </flux:button>

                </div>
                <div class="space-y-4">



                </div>
            </div>
        </div>
    @endif



    <flux:modal name="matcher" class="md:w-96" wire:model.self="modal" :dismissible="false" :closable="false">
        <div class="space-y-6">
            @if ($startButton)
                <div>
                    <flux:heading size="lg">Ready For A Match</flux:heading>
                    <flux:text class="mt-2">Best Of Luck.</flux:text>
                </div>
                <div class="flex justify-center items-center">
                    <flux:button wire:click="StartRound">Start Match</flux:button>
                </div>
            @endif
            @if ($roundWinner)
                <div>
                    <flux:heading size="lg">Congrats! {{ $winnerName }}</flux:heading>
                    <flux:text class="mt-2">You Have Won The Round</flux:text>
                </div>
                <div>
                    <flux:heading size="lg">Scores: {{ $round->player1_score }} - {{ $round->player2_score }}</flux:heading>
                    <flux:text class="mt-2">{{ $match->player1->name }} - {{ $match->player2->name }}</flux:text>

                </div>


            @endif




            <div class="flex">
                <flux:spacer />

            </div>
        </div>
    </flux:modal>





</div>