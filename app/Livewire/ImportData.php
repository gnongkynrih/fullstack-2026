<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mary\Traits\Toast;

class ImportData extends Component
{
    use WithFileUploads;
    use Toast;
    #[Validate('required|file|mimes:xlsx,xls|max:10240')]
    public $file;

    public $importing = false;
    public $importResults = [];

    public function import()
    {
        $this->validate();

        $this->importing = true;
        $this->importResults = [];
       
        try {
            $this->importMenuItem();

            $this->toast(
                type: 'success',
                title: 'Import Successful',
                description: $this->getSuccessMessage()
            );

            $this->reset(['file']);
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Import Failed',
                description: $e->getMessage()
            );
        } finally {
            $this->importing = false;
        }
    }

    private function importMenuItem()
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        Excel::import(new class($successCount, $errorCount, $errors) implements \Maatwebsite\Excel\Concerns\ToCollection {
            private $successCount;
            private $errorCount;
            private $errors;

            public function __construct(&$successCount, &$errorCount, &$errors)
            {
                $this->successCount = &$successCount;
                $this->errorCount = &$errorCount;
                $this->errors = &$errors;
            }

            public function collection(Collection $rows)
            {
                //get the header or first row
                $header = $rows->shift()->map(fn($col) => trim(strtolower($col)))->toArray();
                try {
                    \DB::beginTransaction();
                    foreach ($rows as $index => $row) {
                        $rowNumber = $index + 2; //row number starts from 2
                        
                        if ($row->filter()->isEmpty()) {
                            continue;
                        }

                        //get the data of the current row
                        $data = array_combine($header, $row->toArray());
                      
                        //check if the menu category exist
                        $menuCategory = MenuCategory::where('name', $data['category'])->first();
                        if($menuCategory == null){
                            $menuCategory = MenuCategory::create([
                                'name' => $data['category'],
                            ]);
                        }

                        if(empty($data['name']) || empty($data['price']) || empty($data['category'])){
                            continue;
                        }
                        MenuItem::updateOrCreate([
                            'name' => $data['name'], //searches if the name exist if not it will create else update
                        ],[
                              
                                'description' => $data['description'] ?? '',
                                'price' => $data['price'],
                                'menu_category_id' => $menuCategory->id,
                                'is_active' => true,
                            ]);

                       
                        $this->successCount++;
                    }
                    \DB::commit();
                 } catch (\Exception $e) {
                    \DB::rollBack();
                        Log::error("Error in row {$rowNumber}: " . $e->getMessage());
                        $this->errorCount++;
                        $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    }
            }
        }, $this->file->getRealPath());

        $this->importResults = [
            'success' => $successCount,
            'errors' => $errorCount,
            'error_details' => $errors,
        ];
    }

    private function getSuccessMessage()
    {
        $success = $this->importResults['success'] ?? 0;
        $errors = $this->importResults['errors'] ?? 0;
        
        $message = "{$success} records imported successfully.";
        
        if ($errors > 0) {
            $message .= " {$errors} records failed.";
        }
        
        return $message;
    }

    public function render()
    {
        return view('livewire.import-data');
    }
}
