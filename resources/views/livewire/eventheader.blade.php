<?php

use Livewire\Volt\Component;

new class extends Component {
    public $event;
    public function mount($event)
    {
        $this->event = $event;
    }
}; ?>

<div>
     <!-- ========== HEADER ========== -->
    <div class="relative overflow-hidden ">
        <div class="relative z-10 mt-3 max-w-6xl mx-auto px-6  pt-0">
            
            <!-- Logo and name Section -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:gap-8 mb-10">
                <!-- Logo -->
                @if ($event->logo_path)
                    <img src="{{ Storage::url($event->logo_path) }}" alt="{{ $event->name }} logo"
                        class="h-24 w-24 object-cover rounded-xl " />
                @else
                    <div class="h-24 w-24 bg-gray-200 flex items-center justify-center rounded-xl text-gray-500">
                        <div class="flex-shrink-0 mb-6 lg:mb-0">
                            <div
                                class="w-24 h-24 lg:w-32 lg:h-32 bg-gradient-to-br from-blue-500 to-purple-600 dark:from-blue-400 dark:to-purple-500 rounded-2xl flex items-center justify-center text-5xl lg:text-6xl shadow-xl dark:shadow-2xl">
                                🏆
                            </div>
                        </div>
                    </div>
                @endif
                <!-- name and Info -->
                <div class="flex-1">
                    <div class="flex w-full">
                        <div class="flex-1">

                            <h1
                                class="text-4xl capitalize lg:text-5xl font-black text-gray-900 dark:text-white mb-2 leading-tight">
                                {{ $event->name }}
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-slate-400 font-medium">
                                {{ $event->slogan ? $event->slogan : 'Compete against the best players worldwide'}}
                            </p>
                        </div>
                        {{-- <div class="mt-8">
                            <a href="{{ route('match.generator', $event->id) }}">
                                <flux:button variant="primary">Generate Matches</flux:button>
                            </a>
                        </div> --}}
                    </div>


                </div>
            </div>


        </div>
    </div>
</div>
