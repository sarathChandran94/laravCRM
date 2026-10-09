<?php

use Livewire\Component;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Deal;

new class extends Component
{
    public function with() {
        return [
            'totalCustomers' => Customer::count(),
            'totalLeads' => Lead::count(),
            'totalQualified' => Lead::where("status", "qualified")->count(),
            'totalConverted' => Lead::where("status", "converted")->count(),
            'recentLeads' => Lead::latest()->take(5)->get(),
            'totalDeals' => Deal::count(),
            'openDeals' => Deal::whereIn('stage', [
                            'prospecting',
                            'qualification',
                            'proposal',
                            'negotiation',
                        ])->count(),
            'wonDeals' => Deal::where('stage', 'closed_won')->count(),
            'totalDealValue' => Deal::sum('amount'),
            'wonDealValue' => Deal::where('stage', 'closed_won')->sum('amount'),
            'pipelineValue' => Deal::whereIn('stage', [
                                'prospecting',
                                'qualification',
                                'proposal',
                                'negotiation',
                            ])->sum('amount'),
            'dealStageCounts' => Deal::selectRaw('stage, COUNT(*) as total')
                                    ->groupBy('stage')
                                    ->pluck('total', 'stage'),
            'recentDeals' => Deal::with('customer')
                                ->latest()
                                ->take(5)
                                ->get(),
        ];
    }
};
?>
<div class="p-3">
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-3">
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center">Total Customers</h2>

        <p class="text-3xl font-bold text-center">
            {{ $totalCustomers }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center">Total Leads</h2>

        <p class="text-3xl font-bold text-center">
            {{ $totalLeads }}
        </p>
    </div>
    <div class="bg-white w-xs   p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center">Qualified Leads</h2>

        <p class="text-3xl font-bold text-center">
            {{ $totalQualified }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center">Converted Leads</h2>

        <p class="text-3xl font-bold text-center">
            {{ $totalConverted }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center"> Total Deals </h2>

        <p class="text-3xl font-bold text-center">
            {{ $totalDeals }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center"> Open Deals </h2>

        <p class="text-3xl font-bold text-center">
            {{ $openDeals }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center"> Won Deals </h2>

        <p class="text-3xl font-bold text-center">
            {{ $wonDeals }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center"> Total Deal Value </h2>

        <p class="text-3xl font-bold text-center">
            ₹{{ number_format($totalDealValue, 2) }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center"> Total Deal Value </h2>

        <p class="text-3xl font-bold text-center">
            ₹{{ number_format($wonDealValue, 2) }}
        </p>
    </div>
    <div class="bg-white w-xs  p-6 rounded-lg shadow-md hover:shadow">
        <h2 class="text-gray-500 text-center"> Total Deal Value </h2>
        
        <p class="text-3xl font-bold text-center">
            ₹{{ number_format($pipelineValue, 2) }}
        </p>
    </div>
    
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 py-3">
    <div class="w-sm bg-white rounded-xl shadow border p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            Deal Stage Breakdown
        </h2>

        @php
            $totalStageDeals = $dealStageCounts->sum();
        @endphp

        <div class="space-y-5">
            @foreach ([
                'prospecting' => ['label' => 'Prospecting', 'color' => 'bg-gray-500'],
                'qualification' => ['label' => 'Qualification', 'color' => 'bg-blue-500'],
                'proposal' => ['label' => 'Proposal', 'color' => 'bg-yellow-500'],
                'negotiation' => ['label' => 'Negotiation', 'color' => 'bg-purple-500'],
                'closed_won' => ['label' => 'Closed Won', 'color' => 'bg-green-500'],
                'closed_lost' => ['label' => 'Closed Lost', 'color' => 'bg-red-500'],
            ] as $stage => $details)
                @php
                    $count = $dealStageCounts[$stage] ?? 0;

                    $percentage = $totalStageDeals > 0
                        ? ($count / $totalStageDeals) * 100
                        : 0;
                @endphp

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">
                            {{ $details['label'] }}
                        </span>

                        <span class="text-sm text-gray-500">
                            {{ $count }} deals ({{ number_format($percentage, 1) }}%)
                        </span>
                    </div>

                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div
                            class="{{ $details['color'] }} h-2.5 rounded-full transition-all duration-300"
                            style="width: {{ $percentage }}%"
                        ></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="w-sm bg-white rounded-xl shadow border p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            Recent Deals
        </h2>

        @if ($recentDeals->isEmpty())
            <p class="text-sm text-gray-500">
                No deals have been created yet.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase border-b">
                        <tr>
                            <th class="py-3 pr-4">Deal</th>
                            <th class="py-3 pr-4">Customer</th>
                            <th class="py-3 pr-4">Amount</th>
                            <th class="py-3 pr-4">Stage</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($recentDeals as $deal)
                            <tr class="border-b last:border-0">
                                <td class="py-3 pr-4 font-medium text-gray-900">
                                    {{ $deal->title }}
                                </td>

                                <td class="py-3 pr-4 text-gray-600">
                                    {{ $deal->customer->name }}
                                </td>

                                <td class="py-3 pr-4 text-gray-600 whitespace-nowrap">
                                    ₹{{ number_format($deal->amount, 2) }}
                                </td>

                                <td class="py-3 pr-4">
                                    <span class="inline-block rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $deal->stage)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

        <div class="w-sm bg-white p-6 rounded-xl border shadow">
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
</div>