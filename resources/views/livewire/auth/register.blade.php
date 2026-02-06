<div class="max-w-7xl mx-auto p-6 bg-neutral-50 min-h-screen flex justify-center">
  <div class="w-full max-w-6xl">
    <!-- User Management Card -->
    <x-card shadow separator class="bg-white mb-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-2xl font-bold text-primary-600">User Management</h1>
          <p class="mt-1 text-sm text-gray-600">Manage users and their roles</p>
        </div>
        <x-button 
          label="Add User" 
          wire:click="openModal" 
          icon="o-plus" 
          class="bg-primary-500 hover:bg-primary-600 text-white"
        />
      </div>

      <!-- Users Table -->
      <x-table :headers="[
        ['key' => 'name', 'label' => 'Name', 'class' => 'text-primary-700 font-semibold'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'role', 'label' => 'Role'],
        ['key' => 'actions', 'label' => 'Actions', 'class' => 'text-right']
      ]" :rows="$this->users->toArray()">
        
        @scope('cell_name', $user)
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
              <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <span class="font-medium text-gray-900">{{ $user['name'] }}</span>
          </div>
        @endscope

        @scope('cell_email', $user)
          <span class="text-gray-700">{{ $user['email'] }}</span>
        @endscope

        @scope('cell_role', $user)
          @if(isset($user['roles']) && count($user['roles']) > 0)
            <x-badge value="{{ $user['roles'][0]['name'] }}" class="badge-primary" />
          @else
            <x-badge value="No Role" class="badge-warning" />
          @endif
        @endscope

        @scope('cell_actions', $user)
          <div class="flex gap-2 justify-end">
            <x-button 
              icon="o-pencil" 
              wire:click="edit({{ $user['id'] }})" 
              spinner 
              class="btn-sm bg-secondary-200 text-primary-600 hover:bg-secondary-300"
              tooltip="Edit user"
            />
            <x-button 
              icon="o-trash" 
              wire:click="confirmDelete({{ $user['id'] }})" 
              spinner 
              class="btn-sm bg-red-100 text-red-600 hover:bg-red-200"
              tooltip="Delete user"
            />
          </div>
        @endscope
      </x-table>
    </x-card>

    <!-- Modal -->
    <x-modal wire:model="showModal" title="{{ $isEditing ? 'Edit User' : 'Create User' }}" subtitle="{{ $isEditing ? 'Update user information' : 'Add a new user to the system' }}" class="backdrop-blur">
      <x-form wire:submit.prevent="store">
        <div class="space-y-4">
            <p class="text-sm font-semibold">Role</p>
            <div class="flex justify-centre gap-2">
                @foreach($roles as $role)
                    <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all {{ $selectedRole === $role->name ? 'border-primary-500 bg-primary-50 shadow-sm' : 'border-gray-200 hover:border-primary-300' }}">
                        <input 
                            type="radio" 
                            wire:model.live="selectedRole" 
                            value="{{ $role->name }}" 
                            class="sr-only"
                        >
                        <span 
                            class="text-sm font-semibold {{ $selectedRole === $role->name ? 'text-primary-700' : 'text-gray-600' }}"
                            >{{ $role->name }}
                        </span>
                    </label>
                @endforeach
            </div>
          <x-input
            label="Full Name"
            wire:model="name"
            type="text"
            placeholder="Enter your full name"
            icon="o-user"
          />

          <x-input
            label="Email Address"
            wire:model="email"
            type="email"
            placeholder="Enter your email"
            icon="o-envelope"
          />

          <x-input
            label="Password"
            wire:model="password"
            type="password"
            placeholder="{{ $isEditing ? 'Leave blank to keep current password' : 'Create a password' }}"
            icon="o-lock-closed"
            hint="{{ $isEditing ? 'Optional - leave blank to keep current password' : 'At least 4 characters' }}"
          />

          <x-input
            label="Confirm Password"
            wire:model="password_confirmation"
            type="password"
            placeholder="Confirm your password"
            icon="o-lock-closed"
          />
        </div>

        <x-slot:actions class="mt-6">
            <x-button
                label="Close"
                wire:click="closeModal"
                class="btn-ghost btn-sm"
            />
            <x-button
                label="{{ $isEditing ? 'Update User' : 'Create User' }}"
                type="submit"
                spinner
                class="w-full bg-primary-500 hover:bg-primary-600 text-white"
                icon="{{ $isEditing ? 'o-pencil' : 'o-user-plus' }}"
            />
        </x-slot:actions>
      </x-form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmDeleteModal" title="Delete User?" class="backdrop-blur">
      <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
          <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div>
          <p class="text-gray-700 font-medium mb-2">Are you sure you want to delete this user?</p>
          @if($deletingUser)
            <p class="text-sm text-gray-500">User: <strong>{{ $deletingUser->name }}</strong> ({{ $deletingUser->email }})</p>
            <p class="text-sm text-gray-500">This action cannot be undone. The user will be permanently removed from the system.</p>
          @endif
        </div>
      </div>

      <x-slot:actions>
        <x-button label="Cancel" wire:click="$set('confirmDeleteModal', false)" />
        <x-button 
          label="Delete User" 
          wire:click="delete" 
          spinner 
          class="bg-red-500 hover:bg-red-600 text-white"
          icon="o-trash"
        />
      </x-slot:actions>
    </x-modal>
  </div>
</div>
