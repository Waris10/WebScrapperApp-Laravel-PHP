import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();



let userId = document.querySelector("meta[name='user-id']").getAttribute('content');
window.Echo.private(`private.scraper.${userId}`)
    .subscribed(() => {
        console.log("Subscribed to Create Channel");
    }).listen('.scraper-created', (e) => {
        //console.log("Scraper Created Event:", e);
        let newItem = `
            <li class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer text-sm opacity-0 transform translate-x-4 transition-all duration-300">
                ${e.url.substring(0, 20)}...
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

    });


window.Echo.private(`scraper.${userId}`)
    .subscribed(() => {
        console.log("Subscribed to Delete Channel");
    })
    .listen('.scraper-deleted', (e) => {
        console.log('ScraperDeleted event received:', e.scraperId);

        // Remove from sidebar history
        $(`[data-id="${e.scraperId}"]`).fadeOut(300, function () {
            $(this).remove();
        });

        // Remove from chat messages
        $(`#chat-box div[data-id="${e.scraperId}"]`).fadeOut(300, function () {
            $(this).remove();
        });

        // let length = $('#chat-box').children('.chat-entry').length
        // console.log(length);
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

//This will add the welcome message immediately all messages are deleted

