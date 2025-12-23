<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Tournament;
use Flux\Flux;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $events;

    public $event_id;
    public $name;
    public $logo; // file upload
    public $existing_logo; // path for preview
    public $tournament;

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $tournament = Tournament::where('created_by', auth()->id())->first();
        $this->tournament = $tournament;
        $this->events = $tournament->events;
    }
    public function editEvent($eventId)
    {
        $event = Event::find($eventId);
        if ($event) {
            $this->event_id = $event->id;
            $this->name = $event->name;
            $this->existing_logo = $event->logo_path;
            Flux::modal('generateEvents')->show();
        }

    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048', // 2MB limit
        ]);

        // Handle file upload
        $logoPath = $this->existing_logo;
        if ($this->logo) {
            $logoPath = $this->logo->store('tournaments/logos', 'public');
        }

        Event::updateOrCreate(
            ['id' => $this->event_id],
            [
                'name' => $this->name,
                'tournament_id' => $this->tournament->id,
                'logo_path' => $logoPath,
            ]
        );

        $this->reset(['logo']);
        $this->existing_logo = $logoPath;

        // Flux::toast('Tournament saved successfully! 🎉')->success();
        Flux::modal('generateEvents')->close();
        $this->mount();
    }
    public function resetprops()
    {
        $this->event_id = null;
        $this->name = '';
        $this->logo = null;
        $this->existing_logo = null;
    }
}; ?>

<div>
    <!-- ========== HEADER ========== -->
    <header class="flex flex-wrap lg:justify-start lg:flex-nowrap z-50 w-fullx` py-7 pt-0">
        <nav
            class="relative max-w-7xl w-full flex flex-wrap lg:grid lg:grid-cols-12 basis-full items-center px-4 md:px-6 lg:px-8 mx-auto">
            <div class="lg:col-span-3 flex items-center">
                <!-- Logo -->
                <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80"
                    href="index.html" aria-label="Preline">
                    Manage Events
                </a>
                <!-- End Logo -->

                <div class="ms-1 sm:ms-2">

                </div>
            </div>

            <!-- Button Group -->
            <div class="flex items-center gap-x-1 lg:gap-x-2 ms-auto py-1 lg:ps-6 lg:order-3 lg:col-span-3">
                <flux:modal.trigger name="generateEvents">

                    <button wire:click="resetprops" type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-transparent bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-hidden focus:bg-yellow-500 transition disabled:opacity-50 disabled:pointer-events-none">
                        Create Event
                    </button>
                </flux:modal.trigger>

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
    <flux:modal name="generateEvents" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $event_id ? 'Edit Tournament' : 'Create Tournament' }}</flux:heading>
                <flux:text class="mt-2">Make changes to your personal details.</flux:text>
            </div>
            <flux:input wire:model="name" label="Name" placeholder="Event name" />
            <div class="space-y-3">
                <flux:input type="file" accept="image/png, image/jpeg" wire:model="logo" label="Logo" />

                @if ($logo)
                    <p class="text-sm text-gray-500">Preview (new upload):</p>
                    <img src="{{ $logo->temporaryUrl() }}" class="h-20 rounded-xl border" />
                @elseif ($existing_logo)
                    <p class="text-sm text-gray-500">Current Logo:</p>
                    <img src="{{ Storage::url($existing_logo) }}" class="h-20 rounded-xl border" />
                @endif
            </div>
            <div class="flex">
                <flux:spacer />
                <flux:button wire:click="save" variant="primary">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>
    <!-- Grid -->

    <!-- End Grid -->
    <div class=" grid gap-5 grid-cols-1  md:grid-cols-2 lg:grid-cols-3 ">
        @forelse ($events as $event)

            <!-- Match Card 3 -->

            {{-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-5 border-l-4 border-yellow-500 relative">
                <div class="flex items-center gap-4">
                    <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 flex-1">
                        @if ($event->logo_path)
                            <flux:avatar size="lg" src="{{ Storage::url($event->logo_path) }}" />
                        @else
                            <flux:avatar name="{{ $event->name }}" />
                        @endif

                        <div class="">

                            <span class="text-gray-800 capitalize dark:text-white font-semibold">
                                {{ $event->name }}
                            </span>
                            <div class="">
                                <flux:text variant="muted" class="text-sm">
                                    {{ $event->tournament->name }}
                                </flux:text>
                            </div>
                        </div>

                    </a>

                    <button wire:click="editEvent({{ $event->id }})" type="button"
                        class="absolute top-4 right-4 text-gray-500 hover:text-yellow-500">
                        <flux:icon.pencil-square variant="micro" />
                    </button>
                </div>

            </div> --}}
            <!-- Grid -->

            <!-- End Grid -->
            <!-- Card -->
          

            <div class="p-4 border border-gray-200 rounded-lg dark:border-neutral-700">
                <flux:avatar name="Caleb Porzio" src="{{$event->logo_path ? Storage::url($event->logo_path) : '' }}" />

                

                <p class="font-semibold capitalize mt-3 text-lg text-gray-800 dark:text-neutral-200">
                    {{ $event->name }}
                </p>

                <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">
                    {{ $event->description }}
                </p>
                <div class="">
                    <div class="">
                        <flux:button wire:navigate href="{{ route('event.matches', $event->id) }}" variant="primary" class="mt-4 w-full">
                            Manage Event
                        </flux:button>
                    </div>
                    <div class="">
                        <button wire:navigate href="{{ route('event.players', $event->id) }}" type="button" class="mt-2 w-full text-gray-500 hover:text-yellow-500 border border-gray-300 rounded-lg px-4 py-2">
                            Manage Players
                        </button>
                    </div>
                </div>
            </div>
            <!-- End Card -->

        @empty
            <div class="">
                NO EVENTS CREATED YET!
            </div>

        @endforelse
    </div>

</div>