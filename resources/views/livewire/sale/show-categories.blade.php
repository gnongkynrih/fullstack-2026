<div class="sticky top-0 z-0 bg-white shadow-sm border-b">
  <div class="flex overflow-x-auto py-3 px-4 space-x-2">
      <button
          wire:click="selectCategory('all')"
          class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $selectedCategoryId == 'all' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
      >
          All
      </button>
      @foreach ($menuCategories as $category)
        <button
            wire:click="selectCategory({{ $category->id }})"
            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
        >
            {{ $category->name }}
        </button>
      @endforeach
  </div>
</div>