<?php

use Livewire\Volt\Component;
use App\Models\Tournament;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;
    public $tournament;

    public $permission = false; // Set to true to allow modal dismissal
    public $tournament_id;
    public $name;
    public $start_date;
    public $end_date;
    public $location;
    public $logo; // file upload
    public $existing_logo; // path for preview
    public $slogan;

    public function mount()
    {
        $this->tournament = Tournament::where('created_by', auth()->id())->first();

        if ($this->tournament) {
            $this->tournament_id = $this->tournament->id;
            $this->name = $this->tournament->name;
            $this->start_date = $this->tournament->start_date;
            $this->end_date = $this->tournament->end_date;
            $this->location = $this->tournament->location;
            $this->existing_logo = $this->tournament->logo_path;
            $this->slogan = $this->tournament->slogan;
            // dd($this->slogan);
        } else {
            $this->dispatch('open-modal');
        }
    }

    #[On('open-modal')]
    public function openModal()
    {
        Flux::modal('manage-tournament')->show();
        $this->permission = true;
    }

    public function closeModal()
    {
        Flux::modal('manage-tournament')->close();
        $this->permission = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048', // 2MB limit
            'slogan' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        $logoPath = $this->existing_logo;
        if ($this->logo) {
            $logoPath = $this->logo->store('tournaments/logos', 'public');
        }

        Tournament::updateOrCreate(
            ['id' => $this->tournament_id],
            [
                'name' => $this->name,
                'slogan' => $this->slogan,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'location' => $this->location,
                'logo_path' => $logoPath,
                'created_by' => auth()->id(),
            ]
        );

        $this->reset(['logo']);
        $this->existing_logo = $logoPath;

        // Flux::toast('Tournament saved successfully! 🎉')->success();
        Flux::modal('manage-tournament')->close();
        $this->permission = false;
        $this->mount();
    }
};
?>



<div>
    <div>
        <!-- Tournament Header -->
        @if ($tournament)

            <div class="relative overflow-hidden ">
                <div class="relative z-10 max-w-6xl mx-auto px-6  pt-0">
                    <div wire:click="openModal" class=" absolute top-2 right-4 ">
                        <flux:icon.pencil-square variant="mini" />
                    </div>
                    <!-- Logo and name Section -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:gap-8 mb-10">
                        <!-- Logo -->
                        @if ($tournament->logo_path)
                            <img src="{{ Storage::url($tournament->logo_path) }}" alt="{{ $tournament->name }} logo"
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
                            <h1
                                class="text-4xl capitalize lg:text-5xl font-black text-gray-900 dark:text-white mb-2 leading-tight">
                                {{ $tournament->name }}
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-slate-400 font-medium">
                                {{ $tournament->slogan ? $tournament->slogan : 'Compete against the best players worldwide'}}
                            </p>
                        </div>
                    </div>

                    <!-- Status and Details Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                        <!-- Status Badge -->
                        <div
                            class="bg-white dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-4 border border-gray-200 dark:border-slate-700/60 shadow-sm hover:shadow-md transition-all">
                            <p class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase tracking-wide mb-1">
                                Status</p>
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Ongoing</span>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div
                            class="bg-white dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-4 border border-gray-200 dark:border-slate-700/60 shadow-sm hover:shadow-md transition-all">
                            <p class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase tracking-wide mb-1">
                                Start Date</p>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                {{ Carbon\Carbon::parse($tournament->start_date)->format('M d, Y') }}
                            </p>
                        </div>

                        <!-- End Date -->
                        <div
                            class="bg-white dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-4 border border-gray-200 dark:border-slate-700/60 shadow-sm hover:shadow-md transition-all">
                            <p class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase tracking-wide mb-1">End
                                Date</p>
                            <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                {{ Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') }}
                            </p>
                        </div>

                        <!-- Location -->
                        <div
                            class="bg-white dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-4 border border-gray-200 dark:border-slate-700/60 shadow-sm hover:shadow-md transition-all">
                            <p class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase tracking-wide mb-1">
                                Location</p>
                            <div class="flex items-center gap-1.5">
                                <span class="text-lg">📍</span>
                                <p class="text-base font-bold capitalize text-gray-900 dark:text-white">
                                    {{ $tournament->location }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <livewire:manageevents />
        @endif
        <div>
            <flux:modal name="manage-tournament" class="md:min-w-96" :dismissible="false" :closable="false">
                <form wire:submit.prevent="save">
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="animate-bounce-trophy text-3xl">🏆</span>
                            <div>
                                <h1
                                    class="text-3xl font-black bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                                    {{ $tournament_id ? 'Edit Tournament' : 'Create Tournament' }}
                                </h1>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-slate-400 text-sm mt-3 font-medium">
                            Get ready to host an epic competition. Let's set it up! 🚀
                        </p>
                    </div>

                    <div class="space-y-6">
                        <flux:input wire:model="name" label="Tournament Name" placeholder="Tournament name" />

                        <flux:input wire:model="slogan" label="Tournament Slogan" placeholder="Tournament slogan" />

                        <div class="flex gap-4">
                            <div class="w-full">
                                <flux:input wire:model="start_date" label="Start Date" type="date" />
                            </div>
                            <div class="w-full">
                                <flux:input wire:model="end_date" label="End Date" type="date" />
                            </div>
                        </div>

                        <flux:input wire:model="location" label="Location" placeholder="Location" />

                        <div class="space-y-3">
                            <flux:input type="file"  accept="image/*" wire:model="logo" label="Logo" />

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
                            <flux:button wire:click="closeModal" type="button"  class="mr-3">
                                Cancel
                            </flux:button>
                            <flux:button type="submit" variant="primary">
                                ⚡ {{ $tournament_id ? 'Update Tournament' : 'Launch Tournament' }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </flux:modal>
        </div>
    </div>

</div>