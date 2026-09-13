<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New Post</h1>
        <p class="mt-1 text-sm text-gray-600">Add categories</p>
    </div>

    {{-- form --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">
                    Name
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model.live.debounce="name"
                    placeholder="Enter category name"
                    autofocus
                    class="mt-1 p-1 bg-gray-50 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Description
                </label>
                <div wire:ignore>
                    <input type="hidden" name="description" id="x-description">
                    <trix-editor input="x-description" class="trix-description" x-data
                        x-on:trix-change="$wire.description = $event.target.value"></trix-editor>
                </div>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color -->
            <div class="mb-4">
                <label for="color" class="block text-sm font-medium text-gray-700">Category Color</label>

                <div class="mt-1 flex items-center gap-3">
                    {{-- Native Color Picker Input --}}
                    <input type="color" id="color" wire:model.live="color"
                        class="h-10 w-12 rounded-md border border-gray-300 p-1 cursor-pointer bg-white">

                    {{-- Text Input untuk Menampilkan/Mengetik Kode HEX secara Manual --}}
                    <input type="text" wire:model.live.debounce.300ms="color"
                        placeholder="#6366f1"
                        class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase text-sm">

                    {{-- Preview Badge --}}
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span>Preview:</span>
                        <span class="px-3 py-1 rounded-full text-white text-xs font-semibold" style="background-color: {{ $color ?? '#6366f1' }}">
                            {{ $color ?: '#6366f1' }}
                        </span>
                    </div>
                </div>

            @error('color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button
                    type="submit"
                    class="data-loading:opacity-50 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Create Post
                </button>
                <a
                    href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
