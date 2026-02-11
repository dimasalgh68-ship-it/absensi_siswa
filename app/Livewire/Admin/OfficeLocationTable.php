<?php

namespace App\Livewire\Admin;

use App\Models\OfficeLocation;
use Livewire\Component;
use Livewire\WithPagination;

class OfficeLocationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form fields
    public $showModal = false;
    public $editMode = false;
    public $locationId = null;
    public $name = '';
    public $latitude = '';
    public $longitude = '';
    public $radius_meters = 100;
    public $is_active = true;
    
    // Delete confirmation
    public $showDeleteModal = false;
    public $locationToDelete = null;

    protected $queryString = ['search'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'radius_meters' => 'required|integer|min:10|max:10000',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $location = OfficeLocation::findOrFail($id);
        
        $this->locationId = $location->id;
        $this->name = $location->name;
        $this->latitude = $location->latitude;
        $this->longitude = $location->longitude;
        $this->radius_meters = $location->radius_meters;
        $this->is_active = $location->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $location = OfficeLocation::findOrFail($this->locationId);
            $location->update([
                'name' => $this->name,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius_meters' => $this->radius_meters,
                'is_active' => $this->is_active,
            ]);
            
            session()->flash('message', 'Lokasi berhasil diupdate.');
        } else {
            OfficeLocation::create([
                'name' => $this->name,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius_meters' => $this->radius_meters,
                'is_active' => $this->is_active,
            ]);
            
            session()->flash('message', 'Lokasi berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function confirmDeletion($id)
    {
        $this->locationToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteLocation()
    {
        if ($this->locationToDelete) {
            OfficeLocation::findOrFail($this->locationToDelete)->delete();
            session()->flash('message', 'Lokasi berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->locationToDelete = null;
    }

    public function toggleStatus($id)
    {
        $location = OfficeLocation::findOrFail($id);
        $location->update(['is_active' => !$location->is_active]);
        
        session()->flash('message', 'Status lokasi berhasil diubah.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->locationId = null;
        $this->name = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->radius_meters = 100;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $locations = OfficeLocation::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.office-location-table', [
            'locations' => $locations,
        ]);
    }
}
