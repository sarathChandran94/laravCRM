
<?php

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public Customer $customer;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer->load('deals');
    }
};
?>


<div class="p-6 space-y-6">
    {{-- Page heading and back button --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Customer Details
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                View customer information and associated deals.
            </p>
        </div>

        <a
            href="{{ route('customers') }}"
            wire:navigate
            class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
            Back to Customers
        </a>
    </div>

    {{-- Customer information --}}
    <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $customer->name }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $customer->company ?: 'No company specified' }}
                </p>
            </div>

            <span class="rounded-full px-3 py-1 text-sm font-medium
                {{ $customer->status === 'active'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($customer->status) }}
            </span>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="mt-1 text-sm font-medium text-gray-900">
                    {{ $customer->email ?: 'Not provided' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Phone</p>
                <p class="mt-1 text-sm font-medium text-gray-900">
                    {{ $customer->phone ?: 'Not provided' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-gray-500">Address</p>
                <p class="mt-1 text-sm font-medium text-gray-900">
                    {{ $customer->address ?: 'Not provided' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Customer Since</p>
                <p class="mt-1 text-sm font-medium text-gray-900">
                    {{ $customer->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Related deals --}}
    <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold text-gray-900">
            Related Deals
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Deals associated with {{ $customer->name }}.
        </p>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Deal
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Amount
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Stage
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Expected Close
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($customer->deals as $deal)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $deal->title }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                ₹{{ number_format((float) $deal->amount, 2) }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ ucwords(str_replace('_', ' ', $deal->stage)) }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $deal->expected_close_date
                                    ? \Illuminate\Support\Carbon::parse($deal->expected_close_date)->format('d M Y')
                                    : 'Not specified' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                No deals found for this customer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

