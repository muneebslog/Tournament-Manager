<?php

use Livewire\Volt\Component;
use App\Models\Player;
use App\Models\Event;
// use App\Models\Tournament;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $event;
    public $event_id;
    public $players = [];

    public $player_id;
    public $name;
    public $phone;
    public $subtext;
    public $ranking;
    public $pic;
    public $existing_pic;

    public function mount(Event $eventid)
    {
        $this->event = $eventid;
        $this->event_id = $eventid->id;
        $this->players = $this->event ? $this->event->players : collect();

    }

    public function getData()
    {
        // $this->event = TournamentEvent::find($this->event_id);
        $this->players = $this->event ? $this->event->players : collect();
    }

    public function edit($player_id)
    {
        $player = Player::find($player_id);

        if ($player) {
            $this->player_id = $player->id;
            $this->name = $player->name;
            $this->phone = $player->phone;
            $this->subtext = $player->subtext;
            $this->ranking = $player->ranking;
            $this->existing_pic = $player->pic;
        }

        Flux::modal('manage-player')->show();
    }

    #[On('open-player-modal')]
    public function openModal($id = null)
    {
        // Reset form for new player
        if (!$id) {
            $this->reset(['player_id', 'name', 'phone', 'subtext', 'ranking', 'pic', 'existing_pic']);
        } else {
            $this->edit($id); // load data if editing
        }

        Flux::modal('manage-player')->show();
    }

    public function save()
    {
        $this->validate([
            'event' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'subtext' => 'nullable|string|max:255',
            'ranking' => 'nullable|numeric|min:0',
            'pic' => 'nullable|image|max:3096',
        ]);

        $picPath = $this->existing_pic;

        if ($this->pic) {
            if ($this->existing_pic) {
                Storage::disk('public')->delete($this->existing_pic);
            }
            $picPath = $this->pic->store('players/images', 'public');
        }

        Player::updateOrCreate(
            ['id' => $this->player_id],
            [
                'event_id' => $this->event->id,
                'name' => $this->name,
                'phone' => $this->phone,
                'subtext' => $this->subtext,
                'ranking' => $this->ranking,
                'pic' => $picPath,
            ]
        );

        $this->reset(['pic']);
        $this->existing_pic = $picPath;

        $this->getData();

        // Flux::toast('Player saved successfully! ⚡')->success();
        Flux::modal('manage-player')->close();
        $this->dispatch('player-saved');
    }
};
?>


<div>
    <livewire:eventheader :event="$event" />

    <!-- Table Section -->
    <div class="max-w-[85rem] px-4 py-6 sm:px-6 lg:px-8 lg:py-8 mx-auto">
        <!-- Card -->
        <div class="flex flex-col">
            <div
                class="overflow-x-auto [&::-webkit-scrollbar]:h-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                <div class="min-w-full inline-block align-middle">
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    Players
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-neutral-400">
                                    Add players, edit and more.
                                </p>
                            </div>

                            <div>
                                <div class="inline-flex gap-x-2">

                                    <flux:modal.trigger name="manage-player">

                                        <a
                                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                                <path d="M12 5v14" />
                                            </svg>
                                            Add Players
                                        </a>
                                    </flux:modal.trigger>
                                        <a wire:navigate href="{{ route('event.matches', $event->id) }}">
                                            <flux:button variant="primary" type="button">
                                                Manage Matches
                                            </flux:button>
                                        </a>



                                </div>
                            </div>
                        </div>
                        <!-- End Header -->

                        <!-- Table -->
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                <tr>


                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Name
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                subtext
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Contact
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Ranking
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Created
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-end"></th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @forelse($this->players as $player)
                                    <tr>

                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <div class="flex items-center gap-x-3">
                                                    <flux:avatar name="{{ $player->name }}"
                                                        src="{{ $player->pic ? Storage::url($player->pic) : '' }}" />
                                                    <div class="grow">
                                                        <span
                                                            class="block text-sm font-semibold text-gray-800 capitalize dark:text-neutral-200">{{ $player->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="block text-xs  text-gray-800 dark:text-neutral-200">{{ $player->subtext ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="block text-xs font-semibold text-gray-800 dark:text-neutral-200">{{ $player->phone ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="block text-xs  text-gray-800 dark:text-neutral-200">{{ $player->ranking ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="text-sm text-gray-500 dark:text-neutral-500">{{ Carbon\Carbon::parse($player->created_at)->format('d M, Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-1.5">
                                                <button type="button" wire:click="edit({{ $player->id }})"
                                                    class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-hidden focus:underline font-medium dark:text-blue-500">
                                                    Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-neutral-400">
                                            No players found.
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>
                        <!-- End Table -->

                        <!-- Footer -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-neutral-400">
                                    <span
                                        class="font-semibold text-gray-800 dark:text-neutral-200">{{ count($event->players) }}</span>
                                    results
                                </p>
                            </div>
                        </div>
                        <!-- End Footer -->
                    </div>
                </div>
            </div>
        </div>


        <!-- End Card -->
    </div>
    <!-- End Table Section -->
    <flux:modal name="manage-player" class="md:w-96">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $player_id ? 'Edit Player' : 'Add Player' }}</flux:heading>
                    <flux:text class="mt-2">
                        {{ $player_id ? 'Update player information.' : 'Add a new player to your tournament.' }}
                    </flux:text>
                </div>
                @if ($pic || $existing_pic)

                    <div class=" flex justify-center items-center">
                        @if ($pic)
                            <div class="">
                                <img src="{{ $pic->temporaryUrl() }}" class="h-20 w-20 rounded-full border object-cover" />
                                <p class="text-xs text-gray-500">Preview (new upload):</p>
                            </div>
                        @elseif ($existing_pic)
                            <div class="">
                                <img src="{{ Storage::url($existing_pic) }}"
                                    class="h-20 w-20 rounded-full border object-cover" />
                                <p class="text-xs text-gray-500">Current Image:</p>
                            </div>
                        @endif
                    </div>
                @endif

                <flux:input wire:model="name" label="Name" placeholder="Player name" />
                <flux:input type="file" accept=".jpg, .png, .gif" wire:model="pic" label="Image" />

                <flux:input wire:model="phone" label="Phone" mask=" 9999-9999999" placeholder="Phone number" />

                <div class="flex gap-4">
                    <div class="w-full">
                        <flux:input wire:model="subtext" label="subtext / Organization" placeholder="subtext name" />
                    </div>
                    <div class="w-full">
                        <flux:input wire:model="ranking" label="Ranking" placeholder="Ranking" type="number" />
                    </div>
                </div>

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">
                        ⚡ {{ $player_id ? 'Update Player' : 'Create Player' }}
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

</div>