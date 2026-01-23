<?php

namespace App\Livewire;

use App\Models\RestaurantTable;
use App\Models\TableSession;
use Livewire\Component;
use Mary\Traits\Toast;

class SelectTable extends Component
{
    use Toast;
    public $showGuestForm = false;
    public $showReservationModal = false;
    public $selectedTableId = null;
    public $reservationTableId = null;
    public $guestCount =1;
    public $email = '';

    public function mount(){
        session()->forget('table_session_id');
        session()->forget('table_name');
    }
    public function selectTable($tableId)
    {
        //check if the table already has a session
        $tableSession = TableSession::where('restaurant_table_id', $tableId)
            ->where('status','open')->first();
        if($tableSession){
            session()->put('table_session_id', $tableSession->id);
            session()->put('table_name', $tableSession->table->name);
            //redirect to the table session
            return $this->redirect(route('select-item'));
        }
        
        $this->showGuestForm = true;
        $this->selectedTableId = $tableId;
    }
    public function OpenTable(){
        try{
            \DB::beginTransaction();
                $table = TableSession::create([
                'restaurant_table_id' => $this->selectedTableId,
                'guest_count' => $this->guestCount,
                'email' => $this->email,
                'opened_at' => now(),
                'user_id' => auth()->id(),// stores the user_id of the current user
            ]);

            RestaurantTable::find($this->selectedTableId)
                ->update(['status' => 'occupied']);
                
            \DB::commit();

            session()->put('table_session_id', $table->id);
            
            return $this->redirect(route('select-item'));
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to create/open table',
            );
        }
    }
    public function clearTable(){
        $this->showGuestForm = false;
        $this->selectedTableId = null;
        $this->guestCount = 1;
        $this->email = '';
    }
    public function openReservationModal()
    {
        $this->showReservationModal = true;
        $this->reservationTableId = null;
    }

    public function toggleReservation()
    {
        if (!$this->reservationTableId) {
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Please select a table',
            );
            return;
        }

        $table = RestaurantTable::findOrFail($this->reservationTableId);

        if ($table->status === 'occupied') {
            $this->toast(
                type: 'error',
                title: 'Cannot Reserve',
                description: 'This table is currently occupied',
            );
            return;
        }

        if ($table->status === 'reserved') {
            $table->update(['status' => 'available']);
            $message = "Table {$table->name} is now available";
        } else {
            $table->update(['status' => 'reserved']);
            $message = "Table {$table->name} is now reserved";
        }

        $this->toast(
            type: 'success',
            title: 'Status Updated',
            description: $message,
        );

        $this->showReservationModal = false;
        $this->reservationTableId = null;
    }

    public function render()
    {
        $tables = RestaurantTable::orderBy('name')->get();
        return view('livewire.select-table', [
            'tables' => $tables,
        ]);
    }
}
