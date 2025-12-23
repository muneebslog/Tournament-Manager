<?php

use Livewire\Volt\Component;
use App\Models\Game;
use App\Models\Event;
use App\Models\Player;
use Flux\Flux;

new class extends Component {

    public $event;
    public $numberOfPlayers;
    public $matches = [];
    public $players = [];
    public $selectedMatchId;
    public $max_rounds = 1;
    public $player1_id;
    public $player2_id;
    public $match_date;
    public $court_number;

    public function mount(Event $eventid)
    {
        $this->event = $eventid;
        $this->event = $eventid->load('players', 'games');
        $this->players = $this->event->players ?? collect();
        $this->matches = $this->event->games ?? collect();
        $this->numberOfPlayers = $this->players->count();
        // dd($this->player1_id);

    }

    public function generateRoundsAndMatches()
    {
        $playersCount = $this->numberOfPlayers;
        $totalRounds = (int) ceil(log($playersCount, 2));

        // Dynamic round names
        $roundNames = [];
        if ($totalRounds == 1) {
            $roundNames = ['Final'];
        } elseif ($totalRounds == 2) {
            $roundNames = ['Semifinal', 'Final'];
        } elseif ($totalRounds == 3) {
            $roundNames = ['Quarterfinal', 'Semifinal', 'Final'];
        } elseif ($totalRounds == 4) {
            $roundNames = ['Round 1', 'Quarterfinal', 'Semifinal', 'Final'];
        } elseif ($totalRounds == 5) {
            $roundNames = ['Round 1', 'Round 2', 'Quarterfinal', 'Semifinal', 'Final'];
        } else {
            // 6 or more rounds
            for ($i = 1; $i <= $totalRounds - 3; $i++) {
                $roundNames[] = "Round $i";
            }
            $roundNames = array_merge($roundNames, ['Quarterfinal', 'Semifinal', 'Final']);
        }

        // Create rounds and matches
        for ($i = 1; $i <= $totalRounds; $i++) {
            $roundName = $roundNames[$i - 1] ?? "Round $i";
            $matchesCount = (int) pow(2, $totalRounds - $i);

            $round = Round::create([
                'name' => $roundName,
                'round_number' => $i,
                'matches_count' => $matchesCount,
                'event_id' => $this->event->id,
            ]);

            if ($i == 1) {
                # code...

                for ($j = 1; $j <= $matchesCount; $j++) {
                    Game::create([
                        'round_id' => $round->id,
                        'event_id' => $this->event->id,
                        'player1_id' => null,
                        'player2_id' => null,
                    ]);
                }
            }

        }

        session()->flash('message', 'Rounds and matches created successfully!');
    }




    public function openMatch($id)
    {
        $match = Game::find($id);

        if ($match) {
            $this->selectedMatchId = $match->id;
            if ($match->player1_id) {
                $this->player1_id = $match->player1_id;
                $this->player2_id = $match->player2_id;
            } else {
                $this->player1_id = $this->players[0]->id;
                $this->player2_id = $this->players[0]->id;

            }
            $this->match_date = \Carbon\Carbon::parse($match->match_date)->format('Y-m-d');
            $this->court_number = $match->court_number;
            $this->max_rounds = $match->max_rounds;

            Flux::modal('add-match')->show();
        }
    }

    public function updateMatch()
    {
        // dd($this->player1_id , $this->player2_id, $this->match_date, $this->court_number);
        $var = $this->validate([
            'player1_id' => 'required|different:player2_id',
            'player2_id' => 'required|different:player1_id',
            'match_date' => 'required|date',
            'court_number' => 'required|integer|min:1',
            'max_rounds' => 'required|integer|min:1',
        ]);

        $match = Game::find($this->selectedMatchId);

        if ($match) {
            $match->update([
                'player1_id' => $this->player1_id,
                'player2_id' => $this->player2_id,
                'match_date' => $this->match_date,
                'court_number' => $this->court_number,
                'max_rounds' => $this->max_rounds,
                'title' => Player::find($this->player1_id)->name . ' vs ' . Player::find($this->player2_id)->name,
            ]);
        }

        Flux::modal('add-match')->close();

        // refresh list
        // $this->matches = Game::where('tournament_event_id', $this->tournamentId->id)->get();
        $this->matches = $this->event->matches;

        session()->flash('success', 'Match updated successfully!');
    }

};
?>


<div>
    <livewire:eventheader :event="$event" />
    <!-- ========== HEADER ========== -->
    <header class="flex flex-wrap lg:justify-start lg:flex-nowrap z-50 w-fullx` py-7 pt-0">
        <nav
            class="relative max-w-7xl w-full flex flex-wrap lg:grid lg:grid-cols-12 basis-full items-center px-4 md:px-6 lg:px-8 mx-auto">
            <div class="lg:col-span-3 flex items-center">
                <!-- Logo -->
                <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80"
                    href="index.html" aria-label="Preline">
                    Manage Matches
                </a>
                <!-- End Logo -->

                <div class="ms-1 sm:ms-2">

                </div>
            </div>

            <!-- Button Group -->
            <div class="flex items-center gap-x-1 lg:gap-x-2 ms-auto py-1 lg:ps-6 lg:order-3 lg:col-span-3">
                <flux:modal.trigger name="generateMatches">
                    <button type="button" wire:click="generateMatches"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-transparent bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-hidden focus:bg-yellow-500 transition disabled:opacity-50 disabled:pointer-events-none">
                        Generate Matches
                    </button>
                </flux:modal.trigger>
                <a wire:navigate href="{{ route('event.players', $event->id) }}">
                    <flux:button variant="primary" type="button">
                        Manage Players
                    </flux:button>
                </a>




            </div>
            <!-- End Button Group -->

            <!-- Collapse -->
            <div id="hs-pro-hcail"
                class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow lg:block lg:w-auto lg:basis-auto lg:order-2 lg:col-span-6"
                aria-labelledby="hs-pro-hcail-collapse">
                <div
                    class="flex flex-col gap-y-4 gap-x-0 mt-5 lg:flex-row lg:justify-center lg:items-center lg:gap-y-0 lg:gap-x-7 lg:mt-0">

                </div>
            </div>
            <!-- End Collapse -->
        </nav>
    </header>
    <!-- ========== END HEADER ========== -->





    <div class=" grid grid-cols-2 gap-5 sm:grid-cols-3">
        @forelse ($matches as $match)
            @if ($match->status == 'completed')
                <!-- Match Card Enhanced -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 pb-3 border-l-4 border-green-500">
                    <!-- Header: Serial & Title with Button -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">

                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $match->title }}
                            </span>
                        </div>


                    </div>

                    <!-- Players Section -->
                    <div class="space-y-3 mb-4">
                        <!-- Player 1 -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-800 dark:text-white text-sm font-semibold">
                                {{ $match->player1->name ?? 'Player 1' }}{{ $match->winner_id == $match->player1_id ? '👑' : '' }}
                            </span>
                            <div class=" flex gap-2">

                                @foreach ($match->rounds as $round)
                                    <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-1 rounded mb-2">
                                        <span class="font-medium text-sm"> {{ $round->player1_score }}</span>
                                    </div>

                                @endforeach
                            </div>
                        </div>

                        <flux:separator text="vs" />

                        <!-- Player 2 -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-800 dark:text-white text-sm font-semibold">
                                {{ $match->player2->name ?? 'Player 2' }}{{ $match->winner_id == $match->player2_id ? '👑' : '' }}
                            </span>
                            <div class=" flex gap-2">

                                @foreach ($match->rounds as $round)
                                    <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-1 rounded mb-2">
                                        <span class="font-medium text-sm"> {{ $round->player2_score }}</span>
                                    </div>

                                @endforeach
                            </div>
                        </div>
                    </div>


                    <!-- Footer: Date & Court -->
                    <div class="text-sm text-gray-500 flex justify-between dark:text-gray-400">
                        <span
                            class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                            {{ $match->match_serial_number }}
                        </span>
                        <p>📍 Court #{{ $match->court_number ?? 'N/A' }}</p>
                    </div>


                </div>

            @elseif ($match->status == null || $match->status == 'pending')
                <!-- Match Card Enhanced -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 pb-3 border-l-4 border-yellow-500">
                    <!-- Header: Serial & Title with Button -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">

                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $match->title }}
                            </span>
                        </div>

                        <flux:button wire:click="openMatch({{ $match->id }})" size="sm">
                            @if ($match->player1 && $match->player2)
                                <flux:icon.pencil-square variant="micro" />
                            @else
                                +
                            @endif
                        </flux:button>
                    </div>

                    <!-- Players Section -->
                    <div class="space-y-3 mb-4">
                        <!-- Player 1 -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-800 dark:text-white text-sm font-semibold">
                                {{ $match->player1->name ?? 'Player 1' }}
                            </span>
                        </div>

                        <flux:separator text="vs" />

                        <!-- Player 2 -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-800 dark:text-white text-sm font-semibold">
                                {{ $match->player2->name ?? 'Player 2' }}
                            </span>
                        </div>
                    </div>

                    <!-- Footer: Date & Court -->
                    <div class="text-sm text-gray-500 flex justify-between dark:text-gray-400">
                        <span
                            class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                            {{ $match->match_serial_number }}
                        </span>
                        <p>📍 Court #{{ $match->court_number ?? 'N/A' }}</p>
                    </div>
                    @if ($match->player1 && $match->player2)
                        <div class=" flex justify-end items-center mt-3 ">
                            <flux:button wire:navigate href="{{ route('match.control', $match->id) }}" class=" ">Start Match
                            </flux:button>
                        </div>

                    @endif

                </div>

            @endif

        @empty
            <div class="">
                NO MATCHES CREATED YET!
            </div>

        @endforelse
    </div>
    {{--
    <flux:modal.trigger name="edit-profile">
        <flux:button>Edit profile</flux:button>
    </flux:modal.trigger> --}}

    <flux:modal name="add-match" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Match Data</flux:heading>
                <flux:text class="mt-2">Fill in the details to create a new match.</flux:text>
            </div>

            <flux:select wire:model="player1_id" label="Player 1">
                @forelse ($players as $player)
                    <flux:select.option value="{{ $player->id }}">{{ $player->name }}</flux:select.option>
                @empty
                    <flux:select.option disabled>No players available</flux:select.option>
                @endforelse
            </flux:select>
            <flux:select wire:model="player2_id" label="Player 2">
                @forelse ($players as $player)
                    <flux:select.option value="{{ $player->id }}">{{ $player->name }}</flux:select.option>
                @empty
                    <flux:select.option disabled>No players available</flux:select.option>
                @endforelse
            </flux:select>

            <flux:input wire:model="match_date" label="Match Date" type="date" />
            <flux:input wire:model="court_number" label="Court Number" type="number" placeholder="Court Number" />
            <flux:input wire:model="max_rounds" label="Max Rounds" type="number" placeholder="Max Rounds" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="button" wire:click="updateMatch" variant="primary">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>


</div>