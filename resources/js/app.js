import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();



//const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let userId = document.querySelector("meta[name='user-id']").getAttribute('content');
window.Echo.private(`private.scraper.${userId}`)
    .subscribed(() => {
        console.log("Subscribed to Create Channel");
    }).listen('.scraper-created', (e) => {
        //console.log("Scraper Created Event:", e);
        let url = "http://webscrapperapp.test/scraper"
        let newItem = `
            <li class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer text-sm flex justify-between items-center group" data-id="${e.id}">
                ${e.url.substring(0, 20)}...
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
            <a href="${url}/export/pdf/${e.id}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                PDF</a>
            <a href="${url}/export/csv/${e.id}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                CSV</a>
            <a href="${url}/export/json/${e.id}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                JSON</a>
            <a href="${url}/export/xml/${e.id}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">Export
                XML</a>
            <form data-id="${e.id}"
                class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 deletion-form" role="menuitem">
                <button type="submit" class="w-full text-left">Delete</button>
            </form>
        </div>
    </div>
</div>
            </li>

        `;

        // Find the "Today" section or create it if it doesn't exist
        let todaySection = document.querySelector("#today-history");
        if (!todaySection) {
            let container = document.querySelector("#sidebar-history");
            let newSection = `
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-4 mb-2">Today</h3>
                <ul id="today-history" class="space-y-1">${newItem}</ul>
            `;
            container.insertAdjacentHTML("afterbegin", newSection);
        } else {
            todaySection.insertAdjacentHTML("afterbegin", newItem);
        }


        // Add animation effect after a short delay
        setTimeout(() => {
            let addedItem = document.querySelector("#today-history li:first-child");
            addedItem.classList.remove("opacity-0", "translate-x-4");
        }, 50);

        // window.location.reload();
    });





window.Echo.private(`scraper.${userId}`)
    .subscribed(() => {
        console.log("Subscribed to Delete Channel");
    })
    .listen('.scraper-deleted', (e) => {
        console.log('ScraperDeleted event received:', e.scraperId);
        let id = e.scraperId;

        // Remove from sidebar history
        $(`[data-id="${id}"]`).fadeOut(300, function () {
            $(this).remove();
        });

        // Remove from chat messages
        let chatEntry = $(`#chat-box div[data-id="${id}"]`);
        if (chatEntry.length) {
            chatEntry.fadeOut(300, function () {
                $(this).remove();
            });
        } else {
            console.log("Chat entry not found for ID:", id);
        }

        // Show welcome message if chat is empty
        if ($('#chat-box').children('.chat-entry').length === 2) {
            $('#chat-box').append(`
                <div class="flex justify-center" id="welcome-message">
                    <div class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-4 rounded-lg">
                        Welcome to WebScrapper! Enter a URL to start scraping.
                    </div>
                </div>
            `);
        }
    });
