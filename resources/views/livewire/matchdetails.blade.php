<?php

use Livewire\Volt\Component;
use App\Models\Game;

new class extends Component {
    public $match;
    public function mount(Game $match)
    {
        $this->match = $match;
    }
}; ?>

<div>

    <!-- Header with Dark Mode Toggle -->
    <div class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Match Details</h1>
            {{-- <button onclick="toggleDarkMode()"
                class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
            </button> --}}
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Match Header Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $match->round->event->name }} -
                        {{ $match->round->name }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $match->name }} •
                        {{ $match->created_at->format('M d, Y') }}
                    </p>
                </div>
                <span
                    class="px-4 py-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full font-semibold">Completed</span>
            </div>

            <!-- Teams and Score -->
            <div class="grid grid-cols-3 gap-6 mt-8">
                <!-- Team 1 -->
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $match->team1->name }}</h3>
                    {{-- <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Ranking # {{ $ }}</p> --}}
                    <div class="text-5xl font-bold text-blue-600 dark:text-blue-400">{{ $match->team1_points }}</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Sets Won</p>
                </div>

                <!-- VS Divider -->
                <div class="flex flex-col items-center justify-center">
                    <div class="text-3xl font-bold text-gray-400 dark:text-gray-600 mb-4">VS</div>
                    <div
                        class="w-1 h-16 bg-gradient-to-b from-gray-300 to-gray-200 dark:from-gray-600 dark:to-gray-700 rounded-full">
                    </div>
                </div>

                <!-- Team 2 -->
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $match->team2->name }}</h3>
                    {{-- <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Player #08</p> --}}
                    <div class="text-5xl font-bold text-red-600 dark:text-red-400">{{ $match->team2_points }}</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Sets Won</p>
                </div>
            </div>
        </div>

        <!-- Match Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Match Type</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">Best of {{ $match->bestof }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Duration</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $match->start_time->diffInMinutes($match->end_time ?? $match->updated_at) }} min
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Umpire</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $match->empire_name }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Shuttles Used</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $match->shuttles_used }}</p>
            </div>
        </div>

        <!-- Set Scores -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Set Breakdown</h3>
            <div class="space-y-4">
                <!-- Set 1 -->
                @foreach ($match->scores as $score)

                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Set
                                {{ $score->set_number }}
                            </p>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $score->team1_score }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $match->team1->name }}</p>
                                @if ($score->team1_score > $score->team2_score)
                                    <span
                                        class="ml-8 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded text-sm font-semibold">Won</span>
                                @endif
                            </div>
                            <span class="text-gray-400 dark:text-gray-500 text-xl">-</span>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $score->team2_score }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $match->team2->name }}</p>
                            </div>
                        </div>
                        @if ($score->team2_score > $score->team1_score)
                            <span
                                class="ml-8 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded text-sm font-semibold">Won</span>
                        @endif
                    </div>
                @endforeach

                {{-- <!-- Set 2 -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Set 2</p>
                    </div>
                    <div class="flex items-center gap-8">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">21</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Malik Ahmed</p>
                        </div>
                        <span class="text-gray-400 dark:text-gray-500 text-xl">-</span>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">12</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Hassan Khan</p>
                        </div>
                    </div>
                    <span
                        class="ml-8 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded text-sm font-semibold">Won</span>
                </div> --}}

                {{-- <!-- Set 3 -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg opacity-50">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Set 3</p>
                    </div>
                    <div class="flex items-center gap-8">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Malik Ahmed</p>
                        </div>
                        <span class="text-gray-400 dark:text-gray-500 text-xl">-</span>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Hassan Khan</p>
                        </div>
                    </div>
                    <span
                        class="ml-8 px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded text-sm font-semibold">Not
                        Played</span>
                </div> --}}
            </div>
        </div>

        <!-- Match Timeline / Events -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Match Events</h3>
            <div class="relative">
                <div class="space-y-6">
                    <!-- Event 1 -->
                    @foreach ($match->gameevents as $event)
                        @if ($event->event_type == 'start_round')

                            <!-- Start Round Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-blue-500 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white capitalize">
                                        {{ $event->description }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded text-xs font-semibold mt-2">START_ROUND</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'point')

                            <!-- Point Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-green-500 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->description }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Score:
                                        {{ $event->team1_points_at_event }}-{{ $event->team2_points_at_event }} |
                                        {{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded text-xs font-semibold mt-2">POINT</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'shuttle_change')

                            <!-- Shuttle Change Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-indigo-500 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->description }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">New shuttle introduced |
                                        {{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded text-xs font-semibold mt-2">SHUTTLE_CHANGE</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'yellow_card')

                            <!-- Yellow Card Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-yellow-400 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->description }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Violation: Excessive appeals |
                                        {{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded text-xs font-semibold mt-2">YELLOW_CARD</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'point_deduction')

                            <!-- Point Deduction Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-orange-500 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->description }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Reason: Racket throw |
                                        {{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded text-xs font-semibold mt-2">POINT_DEDUCTION</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'red_card')

                            <!-- Red Card Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-red-600 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->description }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Player Disqualified |
                                        {{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded text-xs font-semibold mt-2">RED_CARD</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'injury')

                            <!-- Injury Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-red-500 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->description }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"> {{ $event->created_at }} |
                                        Duration: 5 minutes</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded text-xs font-semibold mt-2">INJURY</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'end_round')

                            <!-- End Round Event -->
                            <div class="flex gap-4 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-purple-500 mt-1.5"></div>
                                    <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600 my-2"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->description }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Final Score:
                                        {{ $event->team1_points_at_event }}-{{ $event->team2_points_at_event }} |
                                        {{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded text-xs font-semibold mt-2">END_ROUND</span>
                                </div>
                            </div>
                        @elseif ($event->event_type == 'winner')

                            <!-- Winner Event -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-yellow-500 mt-1.5"></div>
                                </div>
                                <div class="pt-1 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white capitalize">{{ $event->description }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">  {{ $event->created_at }}</p>
                                    <span
                                        class="inline-block px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded text-xs font-semibold mt-2">WINNER</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 mt-8 justify-end">
            <a href="{{ route('matches.report', $match->id) }}"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.5 13a3 3 0 01-.369-5.98 5 5 0 119.753 1H7a3 3 0 00-1.5 5.98z"></path>
                </svg>
                Download PDF Report
            </a>
            {{-- <button
                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                Print Report
            </button> --}}
            <button
                class="px-6 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-700 dark:hover:bg-blue-800 transition-colors">
                Publish Match
            </button>
        </div>
    </div>
</div>