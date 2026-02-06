<?php

namespace App\Livewire;

use App\Models\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Profile extends Component
{
    use WithFileUploads;

    public $photo;
    public $currentProfileImage;

    public function mount()
    {
        // Get current profile image for the authenticated user
        $this->currentProfileImage = Image::where('imageable_type', 'App\Models\User')
            ->where('imageable_id', auth()->id())
            ->where('document_type', 'profile')
            ->latest()
            ->first();
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);
    }

    public function save()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);

        if ($this->photo) {
            // Store the uploaded file
            $path = $this->photo->store('profile-images', 'public');

            // Save to database
            Image::create([
                'path' => $path,
                'imageable_type' => 'App\Models\User',
                'imageable_id' => auth()->id(),
                'document_type' => 'profile',
            ]);

            // Update current profile image
            $this->currentProfileImage = Image::where('imageable_type', 'App\Models\User')
                ->where('imageable_id', auth()->id())
                ->where('document_type', 'profile')
                ->latest()
                ->first();

            session()->flash('message', 'Profile image uploaded successfully!');
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
