<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-500 to-primary-800">
            <h1 class="text-2xl font-bold text-white">Profile Settings</h1>
            <p class="text-blue-100 mt-1">Manage your profile information and settings</p>
        </div>

        <div class="p-6">
            <!-- Profile Image Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Picture</h2>

                <div class="flex items-center space-x-6">
                    <!-- Current Profile Image -->
                    <div class="flex-shrink-0">
                        @if($currentProfileImage)
                            <img src="{{ asset('storage/' . $currentProfileImage->path) }}"
                                 alt="Profile Picture"
                                 class="h-32 w-32 rounded-full object-cover border-4 border-gray-200 shadow-lg">
                        @else
                            <div class="h-32 w-32 rounded-full bg-gray-200 border-4 border-gray-200 shadow-lg flex items-center justify-center">
                                <x-icon name="o-user" class="h-16 w-16 text-gray-400" />
                            </div>
                        @endif
                    </div>

                    <!-- Upload Section -->
                    <div class="flex-1">
                        <form wire:submit.prevent="save" enctype="multipart/form-data">
                            <div class="space-y-4">
                                <!-- File Input -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload New Profile Picture
                                    </label>
                                    <input type="file"
                                           wire:model="photo"
                                           id="photo"
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-full file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-blue-50 file:text-blue-700
                                                  hover:file:bg-blue-100
                                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                                    @error('photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <p class="mt-2 text-sm text-gray-500">
                                        PNG, JPG, GIF up to 1MB
                                    </p>
                                </div>

                                <!-- Preview -->
                                @if($photo)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                        <img src="{{ $photo->temporaryUrl() }}"
                                             alt="Preview"
                                             class="h-24 w-24 rounded-full object-cover border-2 border-gray-200">
                                    </div>
                                @endif

                                <!-- Submit Button -->
                                <button type="submit"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <span wire:loading.remove>Upload Image</span>
                                    <span wire:loading>Processing...</span>
                                </button>
                            </div>
                        </form>

                        <!-- Success Message -->
                        @if (session()->has('message'))
                            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('message') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="border-t pt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-900">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-900">
                            {{ auth()->user()->email }}
                        </div>
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-900">
                            {{ auth()->user()->roles->first()?->name ?? 'No Role Assigned' }}
                        </div>
                    </div>

                    <!-- Member Since -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Member Since</label>
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-900">
                            {{ auth()->user()->created_at->format('M j, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
