<?php

use Livewire\Component;
use App\Models\Lead;
use App\Models\Customer;

new class extends Component
{
    public function with() {
        return [
            'totalCustomers' => Customer::count(),
            'totalLeads' => Lead::count(),
            'totalQualified' => Lead::where("status", "qualified")->count(),
            'totalConverted' => Lead::where("status", "converted")->count(),
            'recentLeads' => Lead::latest()->take(5)->get(),
        ];
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white w-xs items-center justify-items-center p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500">Total Customers</h2>

        <p class="text-3xl font-bold">
            {{ $totalCustomers }}
        </p>
    </div>
    <div class="bg-white w-xs items-center justify-items-center p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500">Total Leads</h2>

        <p class="text-3xl font-bold">
            {{ $totalLeads }}
        </p>
    </div>
    <div class="bg-white w-xs items-center justify-items-center p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500">Qualified Leads</h2>

        <p class="text-3xl font-bold">
            {{ $totalQualified }}
        </p>
    </div>
    <div class="bg-white w-xs items-center justify-items-center p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500">Converted Leads</h2>

        <p class="text-3xl font-bold">
            {{ $totalConverted }}
        </p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow mt-6">
    <h2 class="text-xl font-semibold mb-4">
        Recent Leads
    </h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left px-4 py-3">Name</th>
                        <th class="text-left px-4 py-3">Company</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Follow-up Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentLeads as $lead)
                        <tr class="border-b">
                            <td class="px-4 py-3">
                                <a href="{{ route('leads') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $lead->name }}
                                </a>
                            </td>

                            <td class="px-4 py-3">
                                {{ $lead->company }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($lead->status === 'new')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        New
                                    </span>
                                @elseif ($lead->status === 'contacted')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                        Contacted
                                    </span>
                                @elseif ($lead->status === 'qualified')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                        Qualified
                                    </span>
                                @elseif ($lead->status === 'converted')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        Converted
                                    </span>
                                @elseif ($lead->status === 'lost')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        Lost
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                {{ $lead->follow_up_date ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">
                                No recent leads found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('leads') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                View All Leads →
            </a>
        </div>
    </div>
</div>