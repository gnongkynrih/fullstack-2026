<div class="p-6 bg-white rounded-lg">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Import Data</h1>
            <p class="mt-1 text-sm text-gray-600">Import data from Excel or CSV files into your database</p>
        </div>

        <!-- Import Form -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-primary-50 px-6 py-4 border-b border-primary-100">
                <h2 class="text-lg font-medium text-gray-900">Upload File</h2>
            </div>

            <form wire:submit.prevent="import" class="p-6">
                <div class="space-y-6">
                   
                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload File *
                        </label>
                        <div class="mt-1 flex flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <x-icon name="o-photo" class="mx-auto h-12 w-12 text-gray-400" />
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                        <span>Upload a file</span>
                                        <input 
                                            id="file-upload" 
                                            type="file" 
                                            class="sr-only"
                                            wire:model="file"
                                            accept=".xlsx,.xls"
                                        >
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Excel (.xlsx, .xls) or CSV files up to 10MB
                                </p>
                                
                                @if ($file)
                                    <div class="mt-2">
                                        <p class="text-sm text-green-600 font-medium">
                                            Selected: {{ $file->getClientOriginalName() }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <!-- Import Results -->
                    @if(!empty($importResults))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-green-900 mb-2">Import Results</h3>
                        <p class="text-sm text-green-700">
                            ✓ Successfully imported: <strong>{{ $importResults['success'] }}</strong> records
                        </p>
                        @if($importResults['errors'] > 0)
                            <p class="text-sm text-red-700 mt-1">
                                ✗ Failed: <strong>{{ $importResults['errors'] }}</strong> records
                            </p>
                            @if(!empty($importResults['error_details']))
                                <details class="mt-2">
                                    <summary class="text-xs text-red-600 cursor-pointer hover:text-red-800">View error details</summary>
                                    <ul class="mt-2 text-xs text-red-600 space-y-1 list-disc list-inside">
                                        @foreach(array_slice($importResults['error_details'], 0, 10) as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                        @if(count($importResults['error_details']) > 10)
                                            <li class="text-gray-600">... and {{ count($importResults['error_details']) - 10 }} more errors</li>
                                        @endif
                                    </ul>
                                </details>
                            @endif
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3 border-t pt-4">
                    <x-button
                        flat
                        label="Clear"
                        wire:click="$refresh"
                        type="button"
                    />
                    <x-button
                        class="bg-primary-700 hover:bg-primary-800! text-white!"
                        type="submit"
                        label="Import Data"
                        icon="o-arrow-up-tray"
                        wire:loading.attr="disabled"
                        wire:target="import,file"
                        :disabled="!$file"
                    />
                </div>

                <!-- Loading State -->
                <div wire:loading wire:target="import" class=" hidden mt-4">
                    <div class="flex items-center justify-center space-x-2 text-primary-600">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-medium">Importing data, please wait...</span>
                    </div>
                </div>

                <!-- File Upload Progress -->
                <div wire:loading wire:target="file" class="hidden mt-4">
                    <div class="flex items-center justify-center space-x-2 text-primary-600">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-medium">Uploading file...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
