<div class="flex h-full w-full flex-1 flex-col gap-4 p-4">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Categories') }}</flux:heading>
        <flux:button variant="primary" icon="plus" wire:click="openCreate">
            {{ __('New Category') }}
        </flux:button>
    </div>

    <flux:table :paginate="$categories">
        <flux:table.columns>
            <flux:table.column>{{ __('Name') }}</flux:table.column>
            <flux:table.column>{{ __('Description') }}</flux:table.column>
            <flux:table.column>{{ __('Status') }}</flux:table.column>
            <flux:table.column>{{ __('Date Created') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($categories as $category)
                <flux:table.row :key="$category->id" wire:key="category-{{ $category->id }}">
                    <flux:table.cell class="font-medium">{{ $category->name }}</flux:table.cell>
                    <flux:table.cell>{{ Str::limit($category->description, 50) }}</flux:table.cell>
                    <flux:table.cell>
                        @php
                            $statusColor = $category->is_active ? 'green' : 'zinc';
                            $statusLabel = $category->is_active ? __('Active') : __('Inactive');
                        @endphp
                        <flux:badge :color="$statusColor" size="sm" inset="top bottom">
                            {{ $statusLabel }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $category->created_at->format('d M Y') }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            <flux:button size="sm" icon="pencil" variant="ghost" wire:click="openEdit({{ $category->id }})" />
                            <flux:button
                                size="sm"
                                icon="trash"
                                variant="ghost"
                                class="text-red-500 hover:text-red-700"
                                wire:click="delete({{ $category->id }})"
                                wire:confirm="{{ __('Are you sure you want to delete this category?') }}"
                            />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" class="text-center text-zinc-400">
                        {{ __('No categories found.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal name="category-form" class="md:w-[32rem]">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ $editingCategoryId ? __('Edit Category') : __('New Category') }}
                </flux:heading>
                <flux:text class="mt-1">
                    {{ $editingCategoryId ? __('Update the category details below.') : __('Fill in the details for your category.') }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>{{ __('Name') }}</flux:label>
                <flux:input wire:model="name" :placeholder="__('Enter category name')" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Description') }}</flux:label>
                <flux:textarea wire:model="description" :placeholder="__('Describe the category...')" rows="3" />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:checkbox wire:model="is_active">
                    {{ __('Active') }}
                </flux:checkbox>
                <flux:error name="is_active" />
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:button x-on:click="$flux.modal('category-form').close()">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $editingCategoryId ? __('Update') : __('Create') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>