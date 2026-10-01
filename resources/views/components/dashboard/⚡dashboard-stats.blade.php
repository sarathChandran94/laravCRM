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
</div>