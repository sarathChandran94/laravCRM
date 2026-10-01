<?php

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public $search = '';

    public $name = '';
    public $company = '';
    public $email = '';
    public $address = '';
    public $phone = '';
    public $status = 'active';

    public $successMessage = '';

    public $editCustomerId = null;

    public $slNo = 1;

    public function with()  {
        return [
            'customers'=>Customer::query()
            ->where('name','like','%' . $this->search . '%')
            ->orWhere('company','like','%' . $this->search . '%')
            ->orWhere('email','like','%' . $this->search . '%')
            ->orWhere('phone','like','%' . $this->search . '%')
            ->paginate(10),
        ];
    }

    public function updatedSearch() {
        $this->resetPage();
    }

    public function save() {

        if($this->editCustomerId) {

            $customer = Customer::findOrFail($this->editCustomerId);

            $customer->update([
                'name' => $this->name,
                'company' => $this->company,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'status' => $this->status,
            ]);
            
        $this->successMessage = 'Customer Edited Successfully';
            
        } else {
            $this->validate([
                    'name' => 'required',
                    'email' => 'nullable | email',
                    'status' => 'required',
                    ]);
                    
            Customer::create([
                'name' => $this->name,
                'company' => $this->company,
                'email' => $this->email,
                'address' => $this->address,
                'phone' => $this->phone,
                'status' => $this->status,
            ]);

            $this->successMessage = 'Customer added Successfully';

        }
                
        $this->reset([
            'name',
            'company',
            'email',
            'phone',
            'address',
            ]);
        
        $this->status = 'active';

    }

    public function edit($customerId) {

        $this->successMessage = '';

        $customer = Customer::findOrFail($customerId);

        $this->editCustomerId = $customer->id;

        $this->name = $customer->name;
        $this->company = $customer->company;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->status = $customer->status;
    }

    public function cancelEdit() {
        $this->reset([
            'name',
            'company',
            'email',
            'phone',
            'address',
            'editCustomerId',
        ]);

        $this->status = 'active';

        $this->resetValidation();
    }

    public function delete($customerId) {

        $this->successMessage = '';

        $customer = Customer::findOrFail($customerId);

        $customer->delete();

        $this->successMessage = 'Customer deleted successfully.';
    }

};
?>

<div class="items-start justify-items-start">
    <h1 class='text-2xl font-bold mb-4'>Customers</h1>

    <input
        type='text'
        wire:model.live='search'
        placeholder='Search for customers...'
        class="w-md border rounded-lg px-4 py-2 mb-6" />

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">{{$editCustomerId ? 'Edit Customer' : 'Add Customer'}}</h2>

            @if ($successMessage)
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4">
                    {{ $successMessage }}
                </div>
            @endif

            <form wire:submit="save" class="w-md shadow border-2 border-blue-600 bg-gray-50 rounded-lg p-3 space-y-4">

                <div>
                    <label class="block mb-1 font-medium">Name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        placeholder="Enter customer name"
                    />
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium">Company</label>
                    <input
                        type="text"
                        wire:model="company"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        placeholder="Enter company name"
                    />
                </div>

                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <input
                        type="email"
                        wire:model="email"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        placeholder="Enter email address"
                    />
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium">Phone</label>
                    <input
                        type="text"
                        wire:model="phone"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        placeholder="Enter phone number"
                    />
                </div>

                <div>
                    <label class="block mb-1 font-medium">Address</label>
                    <textarea
                        wire:model="address"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        placeholder="Enter address"
                    ></textarea>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Status</label>
                    <select wire:model="status" class="w-full bg-white border rounded-lg px-4 py-2">
                        {{-- <option default value="">Select...</option> --}}
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{$message}}</p>
                    @enderror
                </div>

                @if ($editCustomerId)
                    <button
                        type="button"
                        wire:click="cancelEdit"
                        class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600"
                    >
                    Cancel
                    </button>
                @endif
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700"
                >
                    {{ $editCustomerId ? 'Edit Customer' : 'Add Customer' }}
                </button>

            </form>
        </div>

        <table class="w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-4 py-3">Sl. No.</th>
                    <th class="text-left px-4 py-3">Name</th>
                    <th class="text-left px-4 py-3">Company</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Address</th>
                    <th class="text-left px-4 py-3">Phone</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ( $customers as $customer )
                <tr class="border-t">
                    <td class="px-4 py-3">{{$slNo++}}</td>
                    <td class="px-4 py-3">{{$customer->name}}</td>
                    <td class="px-4 py-3">{{$customer->company}}</td>
                    <td class="px-4 py-3">{{$customer->email}}</td>
                        <td class="px-4 py-3">{{$customer->address}}</td>
                        <td class="px-4 py-3">{{$customer->phone}}</td>
                        <td class="px-4 py-3">
                            @if ($customer->status === 'active')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button
                                type='button'
                                wire:click="edit({{ $customer->id }})"
                                class="text-blue-600 hover:text-blue-800 font-medium px-2">
                                Edit
                            </button>
                            <button
                            type='button'
                            wire:click="delete({{ $customer->id }})"
                            wire:confirm="Are you sure you want to delete this customer?"
                            class="text-red-600 hover:text-red-800 font-medium px-2">
                            Delete
                        </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            No customers found.
                        </td>
                    </tr>   
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $customers->links() }}</div>
</div>