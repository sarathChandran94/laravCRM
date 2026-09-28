<?php

use App\Models\Customer;
use Livewire\Component;

new class extends Component
{
    public $search = '';

    public $name = '';
    public $company = '';
    public $email = '';
    public $address = '';
    public $phone = '';
    public $status = 'active';

    public function with()  {
        return [
            'customers'=>Customer::query()
            ->where('name','like','%' . $this->search . '%')
            ->orWhere('company','like','%' . $this->search . '%')
            ->orWhere('email','like','%' . $this->search . '%')
            ->orWhere('phone','like','%' . $this->search . '%')
            ->get(),
        ];
    }

    public function save() {
        Customer::create([
            'name' => $this->name,
            'company' => $this->company,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'status' => $this->status,
        ]);
    }
};
?>

<div>
    <h1 class='text-2xl font-bold mb-4'>Customers</h1>

    <input
        type='text'
        wire:model.live='search'
        placeholder='Search for customers...'
        class="w-full border rounded-lg px-4 py-2 mb-6" />

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Add Customer</h2>

            <form wire:submit="save" class="space-y-4">

                <div>
                    <label class="block mb-1 font-medium">Name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="Enter customer name"
                    >
                </div>

                <div>
                    <label class="block mb-1 font-medium">Company</label>
                    <input
                        type="text"
                        wire:model="company"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="Enter company name"
                    >
                </div>

                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <input
                        type="email"
                        wire:model="email"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="Enter email address"
                    >
                </div>

                <div>
                    <label class="block mb-1 font-medium">Phone</label>
                    <input
                        type="text"
                        wire:model="phone"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="Enter phone number"
                    >
                </div>

                <div>
                    <label class="block mb-1 font-medium">Address</label>
                    <textarea
                        wire:model="address"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="Enter address"
                    ></textarea>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Status</label>
                    <select wire:model="status" class="w-full border rounded-lg px-4 py-2">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700"
                >
                    Add Customer
                </button>

            </form>
        </div>

        <table class="w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-4 py-3">Name</th>
                    <th class="text-left px-4 py-3">Company</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Address</th>
                    <th class="text-left px-4 py-3">Phone</th>
                    <th class="text-left px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{$customer->name}}</td>
                        <td class="px-4 py-3">{{$customer->company}}</td>
                        <td class="px-4 py-3">{{$customer->email}}</td>
                        <td class="px-4 py-3">{{$customer->address}}</td>
                        <td class="px-4 py-3">{{$customer->phone}}</td>
                        <td class="px-4 py-3">{{$customer->status}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</div>