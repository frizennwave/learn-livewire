<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
        <p class="mt-1 text-sm text-gray-600">Update your blog post</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form wire:submit="update" class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">
                    Title
                </label>
                <input
                    type="text"
                    id="title"
                    wire:model.live.debounce="title"
                    placeholder="Enter post title"
                    class="mt-1 p-1 bg-gray-50 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700">
                    Excerpt
                </label>
                <textarea
                    id="excerpt"
                    wire:model="excerpt"
                    placeholder="A short summary of your post (optional)"
                    rows="2"
                    class="mt-1 p-1 bg-gray-50 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">
                    Content
                </label>
                <div wire:ignore
                    x-data="{
                        content: $wire.entangle('content'),
                    }"
                    x-init="
                        let editor = $refs.trixEditor.editor;
                        editor.loadHTML(content);
                        $refs.trixEditor.addEventListener('trix-change', function(e) {
                            content = e.target.value;
                        });
                    ">
                    <input type="hidden" name="content" id="x-content">
                    <trix-editor input="x-content" class="trix-content" x-ref="trixEditor"></trix-editor>
                </div>

                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Featured Image
                </label>

                @if ($existing_image && !$featured_image)
                    <div class="mt-2 mb-3">
                        <p class="text-sm text-gray-600 mb-1">Current image:</p>
                        <img src="{{ Storage::url($existing_image) }}" class="h-32 w-auto rounded border border-gray-300" alt="Current image">
                    </div>
                @endif

                <input
                    type="file"
                    wire:model="featured_image"
                    accept="image/*"
                    class="mt-1 p-1 bg-gray-50 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100"
                />
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($featured_image)
                    <div class="mt-3" wire:transition>
                        <p class="text-sm text-gray-600 mb-1">New image:</p>
                        <img src="{{ $featured_image->temporaryUrl() }}" class="h-32 w-auto rounded border border-gray-300" alt="Preview">
                    </div>
                @endif

                <div wire:loading wire:target="featured_image" class="mt-2 text-sm text-gray-500">
                    Uploading...
                </div>
            </div>

            <!-- Categories -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Categories (Required)
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                    @foreach ($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-3 flex items-center">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }};"></span>
                                <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('selectedCategories')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tags (Optional)
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                    @foreach ($tags as $tag)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-3 text-sm font-medium text-gray-700">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selectedTags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Select relevant tags to help readers find your content</p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input
                            type="radio"
                            wire:model="status"
                            value="draft"
                            class="h-4 w-4 p-1 bg-gray-50 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                        />
                        <span class="ml-3 block text-sm font-medium text-gray-700">Draft</span>
                    </label>
                    @can('publish posts')
                <label class="flex items-center">
                    <input
                        type="radio"
                        wire:model="status"
                        value="published"
                        class="h-4 w-4 p-1 bg-gray-50 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <span class="ml-3 block text-sm font-medium text-gray-700">Published</span>
                </label>

                <label class="flex items-center">
                    <input
                        type="radio"
                        wire:model="status"
                        value="archived"
                        class="h-4 w-4 p-1 bg-gray-50 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <span class="ml-3 block text-sm font-medium text-gray-700">Archived</span>
                </label>
                @endcan
            </div>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Update Post
            </button>
            <a
                href="{{ route('posts.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
</div>
