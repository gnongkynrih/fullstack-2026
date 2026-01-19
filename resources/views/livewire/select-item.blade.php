<div>
    <div class="flex gap-2">
        @foreach ($menuCategories as $category)
            <div class="p-2 border border-gray-200 rounded-lg p-4">
                <h3>{{ $category->name }}</h3>
            </div>
        @endforeach
    </div>
</div>
