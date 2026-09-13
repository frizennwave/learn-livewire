<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your blog categories</p>
    </div>

    {{-- Filters & Action Button --}}
    <div class="mb-6 bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search categories..." class="w-full rounded-md border-gray-300 shadow-sm
                focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            @can('manage roles')
                <div class="w-auto sm:w-auto">
                    <a href="{{ route('categories.create') }}"
                        class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Category
                    </a>
                </div>
            @endcan
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4" wire:transition>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Categories Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Posts Count
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        <tr wire:key="category-{{ $category->id }}" wire:transition class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($category->color)
                                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }}"></span>
                                    @endif
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{!! $category->description !!}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $category->posts_count ?? $category->posts()->count() }} Posts
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    @can('manage roles')
                                        <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('manage roles')
                                        <button
                                            wire:click="deleteCategory({{ $category->id }})"
                                            wire:confirm="Are you sure you want to delete this category? Posts assigned to this category might be affected."
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                No category found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
