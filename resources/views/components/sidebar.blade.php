@props(['groupedScrapersHistories'])
<div class="flex flex-col w-64 bg-gray-100 dark:bg-secondaryDark border-r border-gray-200 dark:border-gray-700">
    <div class="flex justify-end p-4 w-full">
        <button class="mx-3 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
            title="Search through session">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </button>
        <a href="#" id="new-session-btn" class="btn btn-sm btn-primary" title="New Session">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </a>
    </div>
    <div class="flex-1 overflow-y-auto p-4" id="sidebar-history">
        <!-- Today -->
        @if (!empty($groupedScrapersHistories['today']))
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Today</h3>
        <ul class="space-y-1" id="today-history">
            @foreach ($groupedScrapersHistories['today'] as $history)
            <li class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer text-sm flex justify-between items-center group"
                data-id="{{ $history->id }}">{{ Str::limit($history->url, 20) }}
                <x-history-ellipsis :history="$history" />
            </li>
            @endforeach
        </ul>
        @endif

        <!-- Yesterday -->
        @if (!empty($groupedScrapersHistories['yesterday']))
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-4 mb-2">Yesterday</h3>
        <ul class="space-y-1">
            @foreach ( $groupedScrapersHistories['yesterday'] as $history )
            <li class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer text-sm flex justify-between items-center group"
                data-id="{{ $history->id }}">
                {{Str::limit($history->url, 20)}}
                <x-history-ellipsis :history="$history" />
            </li>
            @endforeach
        </ul>
        @endif

        <!-- Previous 7 Days -->
        @if (!empty($groupedScrapersHistories['previous_7_days']))
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-4 mb-2">Previous 7 Days</h3>
        <ul class="space-y-1">
            @foreach ( $groupedScrapersHistories['previous_7_days'] as $history )
            <li class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer text-sm flex justify-between items-center group"
                data-id="{{ $history->id }}">
                {{Str::limit($history->url, 20)}}
                <x-history-ellipsis :history="$history" />
            </li>
            @endforeach
        </ul>
        @endif

        <!-- Previous 30 Days -->
        @if (!empty($groupedScrapersHistories['previous_30_days']))
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-4 mb-2">Previous 30 Days</h3>
        <ul class="space-y-1">
            @foreach ( $groupedScrapersHistories['previous_30_days'] as $history )
            <li class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer text-sm flex justify-between items-center group"
                data-id="{{ $history->id }}">
                {{Str::limit($history->url, 20)}}
                <x-history-ellipsis :history="$history" />
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Toggle dropdown on ellipsis click
        $('.ellipsis-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling
            const dropdown = $(this).next('.dropdown-menu');
            dropdown.toggleClass('hidden');
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.ellipsis-btn').length && !$(e.target).closest('.dropdown-menu').length) {
                $('.dropdown-menu').addClass('hidden');
            }
        });

        // Handle delete confirmation (optional)
        $('.deletion-form').on('submit', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this scraping history?')) {
                e.preventDefault();
            }
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route('scraper.delete', ['id' => ':id']) }}'.replace(':id', id),
                method: 'DELETE',
                data: {
                _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Scraping history deleted successfully');
                    } else {
                        alert('Deletion failed: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error deleting scraping history: ' + error);
                }
            });
        });
    });
</script>
