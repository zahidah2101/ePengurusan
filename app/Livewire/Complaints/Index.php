<?php

namespace App\Livewire\Complaints;

use App\Models\Complaint;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Complaints')]
#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $title = '';

    public string $description = '';

    public string $status = 'pending';

    public ?int $category_id = null;

    public ?int $editingComplaintId = null;

    public function openCreate(): void
    {
        $this->reset('title', 'description', 'status', 'category_id', 'editingComplaintId');
        $this->status = 'pending';
        Flux::modal('complaint-form')->show();
    }

    public function openEdit(Complaint $complaint): void
    {
        $this->editingComplaintId = $complaint->id;
        $this->title = $complaint->title;
        $this->description = $complaint->description;
        $this->status = $complaint->status;
        $this->category_id = $complaint->category_id;
        Flux::modal('complaint-form')->show();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:pending,in_progress,in_review,rejected,resolved,closed'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        if ($this->editingComplaintId) {
            $complaint = Complaint::findOrFail($this->editingComplaintId);
            $complaint->update($validated);
            Flux::toast(variant: 'success', text: __('Complaint updated.'));
        } else {
            Complaint::create([
                ...$validated,
                'user_id' => Auth::id(),
            ]);
            Flux::toast(variant: 'success', text: __('Complaint submitted.'));
        }

        Flux::modal('complaint-form')->close();
        $this->reset('title', 'description', 'status', 'category_id', 'editingComplaintId');
        $this->resetPage();
    }

    public function delete(Complaint $complaint): void
    {
        $complaint->delete();
        Flux::toast(variant: 'success', text: __('Complaint deleted.'));
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.complaints.index', [
            'complaints' => Complaint::with(['user', 'category'])->latest()->paginate(10),
            'categories' => \App\Models\Category::active()->get(),
        ]);
    }
}
