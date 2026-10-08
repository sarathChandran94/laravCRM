<?php

use App\Models\Lead;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

new class extends Component {

    use withPagination;

    public $search = "";
    public $name = "";
    public $company = "";
    public $email = "";
    public $phone = "";
    public $status = "";
    public $source = "";
    public $notes = "";
    public $follow_up_date = "";
    public $successMessage = "";
    public $editLeadId = null;
    public $slNo = 1;
    
    public function with() {
        
        return [
            'leads'=>Lead::query()
            ->where('name','like', '%' . $this->search . '%')
            ->orWhere('company','like', '%' . $this->search . '%')
            ->orWhere('email','like', '%' . $this->search . '%')
            ->orWhere('phone','like', '%' . $this->search . '%')
            ->orWhere('source','like', '%' . $this->search . '%')
            ->orWhere('status','like', '%' . $this->search . '%')
            ->paginate(5)
            
            ];
            

        
    }

    public function updatedSearch() {
        $this->resetPage();
    }
    
    public function save() {

        $this->successMessage = '';

    if ($this->editLeadId) {
        
        $lead = Lead::findOrFail($this->editLeadId);

        $lead->update([
            "name" => $this->name,
            "company" => $this->company,
            "email" => $this->email,
            "phone" => $this->phone,
            "source" => $this->source,
            "status" => $this->status,
            "notes" => $this->notes,
            "follow_up_date" => $this->follow_up_date,
        ]);

        $this->successMessage = "Lead edited successfully";

    } else {
        $this->validate([
            "name" => "required",
            "email" => "nullable|email",
            "source" => "required",
            "status" => "required",
            "follow_up_date" => "nullable|date",
        ]);

        Lead::create([
            "name" => $this->name,
            "company" => $this->company,
            "email" => $this->email,
            "phone" => $this->phone,
            "source" => $this->source,
            "status" => $this->status,
            "notes" => $this->notes,
            "follow_up_date" => $this->follow_up_date,
        ]);

        $this->successMessage = "Lead added successfully";
    }

    $this->reset([
        "name",
        "company",
        "email",
        "phone",
        "source",
        "status",
        "notes",
        "follow_up_date",
        "editLeadId",
    ]);

}

    public function edit($leadId) {

        $this->successMessage = '';

        $lead = Lead::findOrFail($leadId);

        $this->editLeadId = $lead->id;

        $this->name = $lead->name;
        $this->company = $lead->company;
        $this->email = $lead->email;
        $this->phone = $lead->phone;
        $this->source = $lead->source;
        $this->status = $lead->status;
        $this->notes = $lead->notes;
        $this->follow_up_date = $lead->follow_up_date;

    }

     public function cancelEdit()
{
    $this->reset([
        'name',
        'company',
        'email',
        'phone',
        'source',
        'status',
        'notes',
        'follow_up_date',
        'editLeadId',
        
    ]);

    $this->resetValidation();
}

    public function delete($leadId) {

        $this->successMessage = '';

        $lead = Lead::findOrFail($leadId);

        $lead->delete();

        $this->successMessage = 'Lead deleted successfully.';
    }

    public function leadToCustomer($id) {

         $this->successMessage = '';
    
        $lead = Lead::findOrFail($id);

        if ($lead->status === "converted") {

            $this->successMessage = 'Lead already converted to Customer';
            return;

        }

        DB::transaction(function() use ($lead){

            Customer::create([
                'name'=> $lead->name,
                'company'=> $lead->company,
                'email'=> $lead->email,
                'phone'=> $lead->phone,
            ]);
    
            $lead->update([
                "status" => "converted",
            ]);
    
            $this->successMessage = 'Lead successfuly converted to Customer';
                
        });
        
    }
};
?>

<div class="items-start justify-items-start">
    <h1 class='text-2xl font-bold mb-4'>Leads</h1>


    <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Add Lead</h2>

        @if ($successMessage)
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ $successMessage }}
            </div>
        @endif

        <form class="w-md shadow border-2 border-blue-600 bg-gray-50 rounded-lg p-3 space-y-4" wire:submit="save">

                <div>
                    <label class="block mb-1 font-medium">Name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        placeholder="Enter lead name"
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
                    <label class="block mb-1 font-medium">Source</label>
                    <select
                        wire:model="source"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        >
                        <option value="">Select Source</option>
                        <option value="website">Website</option>
                        <option value="referrel">Referrel</option>
                        <option value="social_media">Social Media</option>
                        <option value="advertisement">Advertisement</option>
                        <option value="cold_call">Cold Call</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Status</label>
                    <select wire:model="status" wire:change='leadToCustomer($event.target.value, $lead->id)' class="w-full bg-white border rounded-lg px-4 py-2">
                        <option value="">Select Status</option>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="qualified">Qualified</option>
                        <option value="lost">Lost</option>
                        <option value="converted">Converted</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 font-medium">Notes</label>
                    <input
                        type="text"
                        wire:model="notes"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                        placeholder="Enter notes"
                    />
                </div>
                <div>
                    <label class="block mb-1 font-medium">Follow-up Date</label>
                    <input
                        type="date"
                        wire:model="follow_up_date"
                        class="w-full bg-white border rounded-lg px-4 py-2"
                    />
                </div>
                <div class="py-2">
                @if ($editLeadId)
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
                    {{ $editLeadId ? 'Update Lead' : 'Add Lead' }}
                </button>
                </div>
        </form>
    </div>

    <div class="p-3">
        <input
        type='text'
        wire:model.live='search'
        placeholder='Search for leads...'
        class="w-md border rounded-lg px-4 py-2 mb-6" 
    />
    </div>
    <table class="w-full bg-white rounded-lg shadow overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left px-4 py-3">ID</th>
                <th class="text-left px-4 py-3">Name</th>
                <th class="text-left px-4 py-3">Company</th>
                <th class="text-left px-4 py-3">Email</th>
                <th class="text-left px-4 py-3">Phone</th>
                <th class="text-left px-4 py-3">Source</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Follow-up-date</th>
                <th class="text-left px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leads as $lead)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $slNo++ }}</td>  
                    <td class="px-4 py-3">{{ $lead->name }}</td>  
                    <td class="px-4 py-3">{{ $lead->company }}</td>  
                    <td class="px-4 py-3">{{ $lead->email }}</td>  
                    <td class="px-4 py-3">{{ $lead->phone }}</td>  
                    <td class="px-4 py-3">{{ $lead->source }}</td>  
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
                            @elseif ($lead->status === 'lost')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-700">
                                    Lost
                                </span>
                            @elseif ($lead->status === 'converted')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Converted
                                </span>
                            @endif
                    </td>  
                    <td class="px-4 py-3">{{ $lead->follow_up_date }}</td>  
                    <td class="px-4 py-3">
                        <button
                            type='button'
                            wire:click="edit({{ $lead->id }})"
                            class="text-blue-600 hover:text-blue-800 font-medium px-2">
                            Edit
                        </button>
                        <button
                            type='button'
                            wire:click="delete({{ $lead->id }})"
                            wire:confirm="Are you sure you want to delete this Lead?"
                            class="text-red-600 hover:text-red-800 font-medium px-2">
                            Delete
                        </button>
                        @if ($lead->status === 'qualified')
                            <button
                                type='button'
                                wire:click="leadToCustomer({{ $lead->id }})"
                                wire:confirm="Are you sure you want to convert this Lead?"
                                class="text-cyan-600 hover:text-cyan-800 font-medium px-2">
                                Convert
                            </button>
                        @endif
                    </td>  
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">
                        No Leads found.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <div class="mt-4">{{ $leads->links() }}</div>
    </table>
</div>