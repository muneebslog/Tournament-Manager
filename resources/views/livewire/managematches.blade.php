<?php

use Livewire\Volt\Component;
use App\Models\GameMatch;
use App\Models\TournamentEvent;
use App\Models\Player;
use Flux\Flux;

new class extends Component {

    public $event;
    public $numberOfPlayers;
    public $matches;
    public $players;
    public $selectedMatchId;
    public $max_rounds = 1;
    public $player1_id;
    public $player2_id;
    public $match_date;
    public $court_number;

    public function mount(TournamentEvent $event)
    {
        $this->event = $event;
        $this->players = $this->event->players;
        $this->matches = $this->event->matches;
        // dd($this->player1_id);

    }

    public function generateMatches()
    {
        // Validate input
        $this->validate([
            'numberOfPlayers' => 'required|integer|min:2'
        ]);
        // Clear existing matches for that tournament (optional)
        // GameMatch::where('tournament_id', $this->tournamentId)->delete();

        // Generate matches
        for ($i = 1; $i <= $this->numberOfPlayers; $i += 2) {
            $player1 = "Player {$i}";
            $player2 = "Player " . ($i + 1 <= $this->numberOfPlayers ? $i + 1 : 'Bye'); // handle odd player count
            $serial = GameMatch::generateSerialNumber($this->event->id);


            GameMatch::create([
                'tournament_event_id' => $this->event->id,
                'title' => "{$player1} vs {$player2}",
                'match_serial_number' => $serial,
            ]);
        }

        // Optionally close the modal or show a success message
        Flux::modal('generateMatches')->close();
        $this->matches = $this->event->matches;

        session()->flash('success', 'Matches generated successfully!');
    }




    public function openMatch($id)
    {
        $match = GameMatch::find($id);

        if ($match) {
            $this->selectedMatchId = $match->id;
            if($match->player1_id){
                $this->player1_id = $match->player1_id;
                $this->player2_id = $match->player2_id;
            }else{
                $this->player1_id=$this->players[0]->id;
                $this->player2_id=$this->players[0]->id;

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

        $match = GameMatch::find($this->selectedMatchId);

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
        // $this->matches = GameMatch::where('tournament_event_id', $this->tournamentId->id)->get();
        $this->matches = $this->event->matches;

        session()->flash('success', 'Match updated successfully!');
    }

};
?>


<div>
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

                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-transparent bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-hidden focus:bg-yellow-500 transition disabled:opacity-50 disabled:pointer-events-none">
                        Generate Matches
                    </button>
                </flux:modal.trigger>



                {{-- <div class="">
                    <button type="button"
                        class="hs-collapse-toggle size-9.5 flex justify-center items-center text-sm font-semibold rounded-xl border border-gray-200 text-black hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-neutral-700 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                        id="hs-pro-hcail-collapse" aria-expanded="false" aria-controls="hs-pro-hcail"
                        aria-label="Toggle navigation" data-hs-collapse="#hs-pro-hcail">
                        <svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6" />
                            <line x1="3" x2="21" y1="12" y2="12" />
                            <line x1="3" x2="21" y1="18" y2="18" />
                        </svg>
                        <svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div> --}}
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
    <flux:modal name="generateMatches" class="md:w-96">
        <form wire:submit.prevent="generateMatches" class="space-y-6">
            <div>
                <flux:heading size="lg">Generate Matches</flux:heading>
                <flux:text class="mt-2">Enter the number of players to auto-generate matches.</flux:text>
            </div>

            <flux:input label="Number Of Players" type="number" placeholder="Number Of Players"
                wire:model="numberOfPlayers" />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Generate Matches</flux:button>
            </div>
        </form>
    </flux:modal>




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
                                {{ $match->player1->name ?? 'Player 1' }}{{ $match->winner_id==$match->player1_id ? '👑' : '' }}
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
                                {{ $match->player2->name ?? 'Player 2' }}{{ $match->winner_id==$match->player2_id ? '👑' : '' }}
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

            @elseif ($match->status == null)
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