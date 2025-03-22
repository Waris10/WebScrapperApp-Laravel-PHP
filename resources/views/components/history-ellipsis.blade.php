<div class="relative hidden group-hover:block">
    <button type="button" class="ellipsis-btn focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
    </button>
    <div class="absolute right-0 mt-2 w-48 bg-[#303030] text-white rounded-md shadow-lg hidden dropdown-menu">
        <div class="py-1" role="menu" aria-orientation="vertical">
            <a href="{{ route('scraper.export.pdf', $history->id) }}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                PDF</a>
            <a href="{{ route('scraper.export.csv', $history->id) }}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                CSV</a>
            <a href="{{ route('scraper.export.json', $history->id) }}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                JSON</a>
            <a href="{{ route('scraper.export.xml', $history->id) }}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                XML</a>
            <form data-id="{{$history->id}}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 deletion-form" role="menuitem">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full text-left">Delete</button>
            </form>
        </div>
    </div>
</div>
