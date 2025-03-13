<div class="p-4 ">
    <form class="max-w-4xl mx-auto" id="scrape-form">
        <div class="flex items-end space-x-2">
            <input name="url" id="url-input" rows="6" class="w-full bg-gray-300 dark:bg-[#303030]
                text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400
                rounded-xl resize-y h-[3rem] max-h-[16rem] border-none" placeholder="Enter a URL to scrape..."
                required></input>

            <button type="submit" class="btn btn-primary flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                </svg>
            </button>
        </div>
        <x-input-error :messages="$errors->get('url')" class="mt-2" />
    </form>
</div>
