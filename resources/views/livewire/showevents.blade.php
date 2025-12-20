<?php

use Livewire\Volt\Component;
use App\Models\TournamentEvent;

new class extends Component {
    public $event;
    public $component;
    public function mount(TournamentEvent $id)
    {
        $this->event = $id;
    }

    

}; ?>

<div>
    <!-- ========== HEADER ========== -->
    <header class="flex  flex-wrap  lg:justify-start lg:flex-nowrap z-50 w-full border  rounded-xl py-4 ">

        <nav
            class="relative max-w-7xl w-full flex flex-wrap lg:grid lg:grid-cols-6 basis-full items-center px-4 md:px-6 lg:px-8 mx-auto">
            <div class="lg:col-span-3 flex gap-3 items-center">
                <flux:avatar src="{{ Storage::url($event->logo) }}" />

                <!-- Logo -->
                <a class="flex-none rounded-xl capitalize text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80"
                    aria-label="Preline">
                    {{ $event->title }}
                </a>
                <!-- End Logo -->

                <div class="ms-1 sm:ms-2">

                </div>
            </div>

            <!-- Button Group -->
            <div class="flex items-center gap-x-1 lg:gap-x-2 ms-auto py-1 lg:ps-6 lg:order-3 lg:col-span-3">
                {{-- <flux:modal.trigger name="generateEvents">

                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-transparent bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-hidden focus:bg-yellow-500 transition disabled:opacity-50 disabled:pointer-events-none">
                        Create Event
                    </button>
                </flux:modal.trigger> --}}

            </div>
            <!-- End Button Group -->


        </nav>
    </header>
    <!-- ========== END HEADER ========== -->
    <!-- Card Blog -->
    <div class="max-w-[85rem] mt-3 px-4 py-2 sm:px-6 lg:px-8 lg:py-4 mx-auto">
        <!-- Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card -->
            <!-- Players Card -->
            <!-- Players Card -->
            <flux:button wire:click="$set('component', 'players')">
                <div class="p-3 md:p-4">
                    <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                        Players
                    </h3>
                </div>
            </flux:button>

            <!-- Matches Card -->
            <flux:button wire:click="$set('component', 'matches')">
                <div class="p-3 md:p-4">
                    <h3 class="text-xl font-semibold text-amber-500">
                        Matches
                    </h3>
                </div>
            </flux:button>


            <!-- End Card -->
        </div>
        <!-- End Grid -->
    </div>
    @if ($component == 'players')
        <livewire:manageplayers :eventid="$event->id" />

    @elseif ($component == 'matches')
        <livewire:managematches :event="$event->id" />
    @endif
</div>