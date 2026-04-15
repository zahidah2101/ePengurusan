<div class="flex h-full w-full flex-1 flex-col gap-4 p-4">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Complaints') }}</flux:heading>
        <flux:button variant="primary" icon="plus" wire:click="openCreate">
            {{ __('New Complaint') }}
        </flux:button>
    </div>

    <flux:table :paginate="$complaints">
        <flux:table.columns>
            <flux:table.column>{{ __('Title') }}</flux:table.column>
            <flux:table.column>{{ __('Category') }}</flux:table.column>
            <flux:table.column>{{ __('Submitted By') }}</flux:table.column>
            <flux:table.column>{{ __('Status') }}</flux:table.column>
            <flux:table.column>{{ __('Date') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($complaints as $complaint)
                <flux:table.row :key="$complaint->id" wire:key="complaint-{{ $complaint->id }}">
                    <flux:table.cell class="font-medium">{{ $complaint->title }}</flux:table.cell>
                    <flux:table.cell>{{ $complaint->category?->name ?? __('No Category') }}</flux:table.cell>
                    <flux:table.cell>{{ $complaint->user->name }}</flux:table.cell>
                    <flux:table.cell>
                        @php
                            $statusColor = match($complaint->status) {
                                'pending'     => 'yellow',
                                'in_progress' => 'blue',
                                'in_review'   => 'orange',
                                'rejected'    => 'red',
                                'resolved'    => 'green',
                                'closed'      => 'zinc',
                            };
                            $statusLabel = match($complaint->status) {
                                'pending'     => __('Pending'),
                                'in_progress' => __('In Progress'),
                                'in_review'   => __('In Review'),
                                'rejected'    => __('Rejected'),
                                'resolved'    => __('Resolved'),
                                'closed'      => __('Closed'),
                            };
                        @endphp
                        <flux:badge :color="$statusColor" size="sm" inset="top bottom">
                            {{ $statusLabel }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $complaint->created_at->format('d M Y') }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            <flux:button size="sm" icon="pencil" variant="ghost" wire:click="openEdit({{ $complaint->id }})" />
                            <flux:button
                                size="sm"
                                icon="trash"
                                variant="ghost"
                                class="text-red-500 hover:text-red-700"
                                wire:click="delete({{ $complaint->id }})"
                                wire:confirm="{{ __('Are you sure you want to delete this complaint?') }}"
                            />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center text-zinc-400">
                        {{ __('No complaints found.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal name="complaint-form" class="md:w-[32rem]">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ $editingComplaintId ? __('Edit Complaint') : __('New Complaint') }}
                </flux:heading>
                <flux:text class="mt-1">
                    {{ $editingComplaintId ? __('Update the complaint details below.') : __('Fill in the details for your complaint.') }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>{{ __('Title') }}</flux:label>
                <flux:input wire:model="title" :placeholder="__('Enter complaint title')" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Description') }}</flux:label>
                <flux:textarea wire:model="description" :placeholder="__('Describe the complaint...')" rows="4" />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Category') }}</flux:label>
                <flux:select wire:model="category_id">
                    <flux:select.option value="">{{ __('Select a category') }}</flux:select.option>
                    @foreach($categories as $category)
                        <flux:select.option :value="$category->id">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="category_id" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Status') }}</flux:label>
                <flux:select wire:model="status">
                    <flux:select.option value="pending">{{ __('Pending') }}</flux:select.option>
                    <flux:select.option value="in_progress">{{ __('In Progress') }}</flux:select.option>
                    <flux:select.option value="in_review">{{ __('In Review') }}</flux:select.option>
                    <flux:select.option value="rejected">{{ __('Rejected') }}</flux:select.option>
                    <flux:select.option value="resolved">{{ __('Resolved') }}</flux:select.option>
                    <flux:select.option value="closed">{{ __('Closed') }}</flux:select.option>
                </flux:select>
                <flux:error name="status" />
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:button x-on:click="$flux.modal('complaint-form').close()">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $editingComplaintId ? __('Update') : __('Submit') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
