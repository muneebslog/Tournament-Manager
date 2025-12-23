<?php

use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {
    public $event;
    public $component;
    public function mount(Event $id)
    {
        $this->event = $id;
    }



}; ?>

<div>
    <!-- ========== HEADER ========== -->
   

    {{-- <livewire:manageplayers :eventid="$event->id" /> --}}
    <livewire:managematches :event="$event->id" />

</div>