<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Round;
use App\Models\Game;

new class extends Component {

    public Event $event;
    public int $numberOfPlayers;

    public function mount(Event $event)
    {
        $this->event = $event->load('players');
        $this->numberOfPlayers = $this->event->players->count();
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


}; ?>

<div>
    <div>
        <p>Number of Players: {{ $numberOfPlayers }}</p>
        <button wire:click="generateRoundsAndMatches" class="px-4 py-2 bg-blue-600 text-white rounded">Generate Rounds &
            Matches</button>

        @if(session()->has('message'))
            <p class="mt-2 text-green-600">{{ session('message') }}</p>
        @endif
    </div>

</div>