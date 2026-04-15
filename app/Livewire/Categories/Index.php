<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Categories')]
#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $name = '';

    public string $description = '';

    public bool $is_active = true;

    public ?int $editingCategoryId = null;

    public function openCreate(): void
    {
        $this->reset('name', 'description', 'is_active', 'editingCategoryId');
        $this->is_active = true;
        Flux::modal('category-form')->show();
    }

    public function openEdit(Category $category): void
    {
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        Flux::modal('category-form')->show();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        if ($this->editingCategoryId) {
            $category = Category::findOrFail($this->editingCategoryId);
            $category->update($validated);
            Flux::toast(variant: 'success', text: __('Category updated.'));
        } else {
            Category::create($validated);
            Flux::toast(variant: 'success', text: __('Category created.'));
        }

        Flux::modal('category-form')->close();
        $this->reset('name', 'description', 'is_active', 'editingCategoryId');
        $this->resetPage();
    }

    public function delete(Category $category): void
    {
        $category->delete();
        Flux::toast(variant: 'success', text: __('Category deleted.'));
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.categories.index', [
            'categories' => Category::latest()->paginate(10),
        ]);
    }
}