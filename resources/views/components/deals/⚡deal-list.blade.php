<?php

use Livewire\Component;
use App\Models\Customer;
use App\Models\Deal;

new class extends Component {

public $customer_id = "";
public $title = "";
public $amount = "";
public $stage = "";
public $expected_close_date = "";
public $notes = "";
public $editDealId = null;
public $successMessage = "";

    public function save() {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'expected_close_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($this->editDealId) {
            $deal = Deal::findOrFail($this->editDealId);

            $deal->update([
                'customer_id' => $this->customer_id,
                'title' => $this->title,
                'amount' => $this->amount,
                'stage' => $this->stage,
                'expected_close_date' => $this->expected_close_date ?: null,
                'notes' => $this->notes ?: null,
            ]);

            $this->successMessage = 'Deal updated successfully.';
        } else {
            Deal::create([
                'customer_id' => $this->customer_id,
                'title' => $this->title,
                'amount' => $this->amount,
                'stage' => $this->stage,
                'expected_close_date' => $this->expected_close_date ?: null,
                'notes' => $this->notes ?: null,
            ]);

            $this->successMessage = 'Deal created successfully.';
        }

        $this->reset([
            'customer_id',
            'title',
            'amount',
            'stage',
            'expected_close_date',
            'notes',
            'editDealId',
        ]);

    $this->resetValidation();
}

public function edit($dealId)
{
    $deal = Deal::findOrFail($dealId);

    $this->editDealId = $deal->id;
    $this->customer_id = $deal->customer_id;
    $this->title = $deal->title;
    $this->amount = $deal->amount;
    $this->stage = $deal->stage;
    $this->expected_close_date = $deal->expected_close_date;
    $this->notes = $deal->notes;

    $this->resetValidation();
}

public function cancelEdit()
{
    $this->reset([
        'customer_id',
        'title',
        'amount',
        'stage',
        'expected_close_date',
        'notes',
        'editDealId',
    ]);

    $this->resetValidation();
}

public function delete($dealId) {
    $deal = Deal::findOrFail($dealId);

    $deal->delete();

    $this->successMessage = 'Deal deleted successfully.';
}

    public function with() {

        return [
            'deals' => Deal::with('customer')->latest()->get(),
            'customers' => Customer::orderBy('name')->get(),

        ];
    }
};
?>

<div>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Deals</h1>

        @if ($successMessage)
            <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                {{ $successMessage }}
            </div>
        @endif

        <div class="mb-6">
            <label for="customer_id" class="block text-sm font-medium mb-2">
                Customer
            </label>

            <select
                id="customer_id"
                wire:model="customer_id"
                class="w-full border rounded-lg px-3 py-2"
            >
                <option value="">Select Customer</option>

                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
            @error('customer_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium mb-2">
                Deal Title
            </label>

            <input
                type="text"
                id="title"
                wire:model="title"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Enter deal title"
            >
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="amount" class="block text-sm font-medium mb-2">
                Amount
            </label>

            <input
                type="number"
                id="amount"
                wire:model="amount"
                step="0.01"
                min="0"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Enter deal amount"
            >
            @error('amount')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="stage" class="block text-sm font-medium mb-2">
                Stage
            </label>

            <select
                id="stage"
                wire:model="stage"
                class="w-full border rounded-lg px-3 py-2"
            >
                <option value="">Select Stage</option>
                <option value="prospecting">Prospecting</option>
                <option value="qualification">Qualification</option>
                <option value="proposal">Proposal</option>
                <option value="negotiation">Negotiation</option>
                <option value="closed_won">Closed Won</option>
                <option value="closed_lost">Closed Lost</option>
            </select>
            @error('stage')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="expected_close_date" class="block text-sm font-medium mb-2">
                Expected Close Date
            </label>

            <input
                type="date"
                id="expected_close_date"
                wire:model="expected_close_date"
                class="w-full border rounded-lg px-3 py-2"
            >
            @error('expected_close_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium mb-2">
                Notes
            </label>

            <textarea
                id="notes"
                wire:model="notes"
                rows="4"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Add notes about this deal"
            ></textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="button"
            wire:click="save"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg"
        >
            {{ $editDealId ? 'Edit Deal' : 'Save Deal' }}
        </button>

        @if ($editDealId)
            <button
                type="button"
                wire:click="cancelEdit"
                class="px-4 py-2 bg-gray-500 text-white rounded-lg"
            >
                Cancel
            </button>
        @endif
        
        <div class="overflow-x-auto">
            <h2 class="text-2xl font-medium mb-6">Recent Deals</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">Title</th>
                        <th class="text-left p-3">Customer</th>
                        <th class="text-left p-3">Amount</th>
                        <th class="text-left p-3">Stage</th>
                        <th class="text-left p-3">Expected Close</th>
                        <th class="text-left p-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($deals as $deal)
                        <tr class="border-b">
                            <td class="p-3">
                                {{ $deal->title }}
                            </td>

                            <td class="p-3">
                                {{ $deal->customer->name }}
                            </td>

                            <td class="p-3">
                                ₹{{ number_format($deal->amount, 2) }}
                            </td>

                            <td class="p-3">
                                @php
                                    $stageClasses = [
                                        'prospecting' => 'bg-gray-100 text-gray-800',
                                        'qualification' => 'bg-blue-100 text-blue-800',
                                        'proposal' => 'bg-yellow-100 text-yellow-800',
                                        'negotiation' => 'bg-purple-100 text-purple-800',
                                        'closed_won' => 'bg-green-100 text-green-800',
                                        'closed_lost' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp

                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $stageClasses[$deal->stage] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucwords(str_replace('_', ' ', $deal->stage)) }}
                                </span>
                            </td>

                            <td class="p-3">
                                {{ $deal->expected_close_date ?? '—' }}
                            </td>
                            <td class="p-3">
                                <button
                                    type="button"
                                    wire:click="edit({{ $deal->id }})"
                                    class="text-blue-600 hover:underline"
                                >
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    wire:click="delete({{ $deal->id }})"
                                    wire:confirm="Are you sure you want to delete this deal?"
                                    class="text-red-600 hover:underline ml-3"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center">
                                No deals found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>