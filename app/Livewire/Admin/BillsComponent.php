<?php

namespace App\Livewire\Admin;

use App\Models\Bill;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BillsComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingBillId = null;

    public $user_id = '';
    public $description = '';
    public $amount = '';
    public $status = 'unpaid';
    public $due_date = '';
    public $proof;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'status' => 'required|in:unpaid,paid',
        'due_date' => 'nullable|date',
        'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($billId)
    {
        $bill = Bill::findOrFail($billId);
        $this->editingBillId = $billId;
        $this->user_id = $bill->user_id;
        $this->description = $bill->description;
        $this->amount = $bill->amount;
        $this->status = $bill->status;
        $this->due_date = $bill->due_date ? $bill->due_date->format('Y-m-d') : '';
        $this->proof = null; // Reset proof for edit
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingBillId = null;
        $this->resetForm();
    }

    public function createBill()
    {
        $this->validate();

        $proofPath = null;
        if ($this->proof) {
            $proofPath = $this->proof->store('proofs', 'public');
        }

        Bill::create([
            'user_id' => $this->user_id,
            'description' => $this->description,
            'amount' => $this->amount,
            'status' => $this->status,
            'due_date' => $this->due_date ?: null,
            'paid_at' => $this->status === 'paid' ? now() : null,
            'proof_path' => $proofPath,
        ]);

        session()->flash('message', 'Tagihan berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateBill()
    {
        $this->validate();

        $bill = Bill::findOrFail($this->editingBillId);
        $bill->update([
            'user_id' => $this->user_id,
            'description' => $this->description,
            'amount' => $this->amount,
            'status' => $this->status,
            'due_date' => $this->due_date ?: null,
            'paid_at' => $this->status === 'paid' ? now() : null,
        ]);

        session()->flash('message', 'Tagihan berhasil diperbarui.');
        $this->closeEditModal();
    }

    public function deleteBill($billId)
    {
        Bill::findOrFail($billId)->delete();
        session()->flash('message', 'Tagihan berhasil dihapus.');
    }

    public function markAsPaid($billId)
    {
        $bill = Bill::findOrFail($billId);
        $bill->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        session()->flash('message', 'Tagihan berhasil ditandai sebagai dibayar.');
    }

    private function resetForm()
    {
        $this->user_id = '';
        $this->description = '';
        $this->amount = '';
        $this->status = 'unpaid';
        $this->due_date = '';
        $this->proof = null;
    }

    public function render()
    {
        $bills = Bill::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%');
                })
                ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $users = User::whereNotIn('group', ['admin', 'superadmin'])->get();

        return view('livewire.admin.bills-component', [
            'bills' => $bills,
            'users' => $users,
        ]);
    }
}
