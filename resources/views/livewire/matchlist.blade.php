<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Game;
use Livewire\Attributes\{Layout, Title};


new
    #[Layout('layouts.guest')]
    #[Title('Event Matches')]
    class extends Component {
    public $events;
    public $matches;
    public function mount()
    {
        $this->events = Event::has('games')->get();
        $this->showMatches($this->events->first()->id);
    }

    public function showMatches($id)
    {
        $this->matches = Game::where('event_id', $id)
            ->whereNotNull('team1_id')
            ->whereNotNull('team2_id')
            ->orderBy('created_at', 'desc')
            ->get();
            // dd($this->matches[0]);
    }
}; ?>

<div>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #475569;
        }

        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .event-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-left: 4px solid #eab308;
            transition: all 0.3s ease;
            position: relative;
        }

        .event-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .event-content {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .event-avatar {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6 0%, #a855f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .event-card img {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            object-fit: cover;
        }

        .event-info {
            flex: 1;
        }

        .event-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
            color: #1e293b;
        }

        .event-subtitle {
            font-size: 12px;
            color: #64748b;
        }

        .event-edit-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #94a3b8;
            transition: all 0.3s ease;
            padding: 4px;
        }

        .event-edit-btn:hover {
            color: #eab308;
            transform: scale(1.1);
        }

        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
            font-size: 16px;
            grid-column: 1 / -1;
        }

        /* Matches Section */
        .matches-section {
            margin-top: 40px;
        }

        .matches-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .match-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 16px;
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .match-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .match-card.completed {
            border-left-color: #22c55e;
        }

        .match-card.pending {
            border-left-color: #eab308;
        }

        .match-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .match-title {
            font-weight: 600;
            font-size: 14px;
            color: #475569;
        }

        .match-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .match-badge.completed {
            background: #dcfce7;
            color: #166534;
        }

        .match-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .match-players {
            margin-bottom: 12px;
        }

        .player-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
            color: #1e293b;
        }

        .player-name {
            font-weight: 500;
            flex: 1;
        }

        .player-score {
            display: flex;
            gap: 4px;
        }

        .round-score {
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
        }

        .vs-separator {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            margin: 6px 0;
        }

        .match-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
        }

        .match-serial {
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 11px;
        }

        .match-action-btn {
            background: none;
            border: 1px solid #3b82f6;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 8px;
            width: 100%;
            color: #3b82f6;
        }

        .match-action-btn:hover {
            background: #3b82f6;
            color: white;
        }

        .winner-crown {
            margin-left: 4px;
        }

        @media (max-width: 768px) {
            .events-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 16px;
            }

            .matches-grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #9333ea);
            color: white;
            font-weight: 300;
            font-size: 1rem;
            padding: 0.50rem 1rem;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(147, 51, 234, 0.3);
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
            opacity: 0.95;
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
    </style>
    <div class="container">
        <div class="header">
            <h1>📋 Events & Matches</h1>
        </div>

        <!-- Events Section -->
        <section>
            <h2 class="section-title">My Events</h2>
            <div class="events-grid">
                @foreach ($events as $event)

                    <!-- Event Card 1 -->
                    <div wire:click="showMatches({{ $event->id }})" class="event-card">
                        <div class="event-content">
                            <div class="event-avatar">T</div>
                            <div class="event-info">
                                <div class="event-title">{{ $event->name }}</div>
                                <div class="event-subtitle">{{ count($event->games) }} matches</div>
                            </div>
                        </div>
                    </div>
                @endforeach



            </div>
        </section>

        <!-- Matches Section -->
        <section class="matches-section">
            <h2 class="section-title">Matches</h2>
            <div class="matches-grid">
                <!-- Completed Match -->
               
                @if ($matches)
                    @foreach ($matches as $match)


                        <div class="match-card completed">
                            <div class="match-header">
                                <span class="match-title">{{ $match->name }}</span>
                                <span class="match-badge completed">{{ $match->status }}</span>
                            </div>

                            <div class="match-players">
                                <div class="player-row">
                                    <span class="player-name">{{ $match->team1->name }}<span
                                            class="winner-crown">{{ $match->winner_team_id == $match->team1_id ? '👑' : '' }}</span></span>
                                    <div class="player-score">
                                        @foreach ($match->scores as $round)
                                            <div class="round-score">{{ $round->team1_score }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="vs-separator">vs</div>
                                <div class="player-row">
                                    <span class="player-name">{{ $match->team2->name }}<span
                                            class="winner-crown">{{ $match->winner_id == $match->team2_id ? '👑' : '' }}</span></span>
                                    @foreach ($match->scores as $round)
                                        <div class="round-score">{{ $round->team2_score }}</div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="match-footer">
                                <span class="match-serial">{{ $match->match_serial_number }}</span>
                                <span>📍 Court #{{ $match->court_number }}</span>
                            </div>
                            <div class="match-footer">
                                <a wire:navigate href="{{ route('match.scoreboard', $match->id) }}" class="btn-primary">View
                                    Score</a>
                            </div>

                        </div>
                    @endforeach

                @endif


            </div>
        </section>
    </div>
</div>