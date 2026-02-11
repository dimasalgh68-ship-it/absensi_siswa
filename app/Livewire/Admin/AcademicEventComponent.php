<?php

namespace App\Livewire\Admin;

use App\Models\AcademicEvent;
use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Jetstream\InteractsWithBanner;

class AcademicEventComponent extends Component
{
    use WithPagination, InteractsWithBanner;

    public $showModal = false;
    public $editingId = null;
    public $title = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $type = 'event';
    public $color = '#3B82F6';
    public $is_active = true;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'type' => 'required|in:holiday,exam,event,meeting,other',
        'color' => 'required|string|max:7',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $event = AcademicEvent::findOrFail($id);
        $this->editingId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->start_date = $event->start_date->format('Y-m-d');
        $this->end_date = $event->end_date->format('Y-m-d');
        $this->type = $event->type;
        $this->color = $event->color;
        $this->is_active = $event->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $event = AcademicEvent::findOrFail($this->editingId);
            $event->update([
                'title' => $this->title,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'type' => $this->type,
                'color' => $this->color,
                'is_active' => $this->is_active,
            ]);
            $this->banner('Event berhasil diperbarui!');
        } else {
            AcademicEvent::create([
                'title' => $this->title,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'type' => $this->type,
                'color' => $this->color,
                'is_active' => $this->is_active,
            ]);
            $this->banner('Event berhasil ditambahkan!');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function delete($id)
    {
        AcademicEvent::findOrFail($id)->delete();
        $this->banner('Event berhasil dihapus!');
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->description = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->type = 'event';
        $this->color = '#3B82F6';
        $this->is_active = true;
    }

    public function render()
    {
        $events = AcademicEvent::orderBy('start_date', 'desc')->paginate(10);
        return view('livewire.admin.academic-event', compact('events'));
    }
}
