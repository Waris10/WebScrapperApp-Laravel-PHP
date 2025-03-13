<x-app-layout>
    <style>
        .loader {
            width: 12px;
            height: 12px;
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="flex h-screen antialiased text-gray-800 dark:text-gray-200 dark:bg-primaryDark">
        <!-- Sidebar -->
        <x-sidebar :groupedScrapersHistories="$groupedScrapersHistories" />
        <div class="flex-1 flex flex-col">
            <x-navigation />
            {{-- Chat Messages --}}
            <div class="flex-1 overflow-y-auto p-4" id="chat-bar">
                <div class="max-w-4xl mx-auto space-y-4" id="chat-box">
                    @if (empty($groupedScrapersHistories['today']) && empty($groupedScrapersHistories['yesterday']) &&
                    empty($groupedScrapersHistories['previous_7_days']) &&
                    empty($groupedScrapersHistories['previous_30_days']))
                    <div class="flex justify-center" id="welcome-message">
                        <div class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-4 rounded-lg">
                            Welcome to WebScrapper! Enter a URL to start scraping.
                        </div>
                    </div>
                    @endif

                    {{-- To display the scraper Messages --}}
                    @foreach (Auth::user()->scrapers as $scraper )
                    <div class="flex justify-end chat-entry" data-id="{{ $scraper->id }}">
                        <div class="bg-[#303030] text-white p-4 rounded-lg max-w-md">
                            {{$scraper->url}}
                        </div>
                    </div>
                    <div class="flex justify-start chat-entry" data-id="{{ $scraper->id }}">
                        <div
                            class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-4 rounded-lg max-w-md">
                            <p>{{$scraper->result}}</p>
                            @if (!empty($scraper->images))
                            <p><strong>Images ({{ count($scraper->images) }}):</strong></p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($scraper->images as $image)
                                <a href="{{ $image }}" target="_blank"><img src="{{ $image }}" alt="Scraped Image"
                                        class="w-20 h-20 object-cover rounded"></a>
                                @endforeach
                            </div>
                            @endif
                            @if (!empty($scraper->videos))
                            <p><strong>Videos ({{ count($scraper->videos) }}):</strong></p>
                            <ul class="list-disc pl-5">
                                @foreach ($scraper->videos as $video)
                                @if (Str::contains($video, ['youtube.com', 'vimeo.com']))
                                <!-- Embedded Video (YouTube/Vimeo) -->
                                <iframe src="{{ $video }}" width="400" height="225" frameborder="0"
                                    allowfullscreen></iframe>
                                @else
                                <!-- Direct Video File -->
                                <video width="400" controls>
                                    <source src="{{ $video }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                @endif
                                @endforeach
                            </ul>
                            @endif
                            @if (!empty($scraper->external_links))
                            <p><strong>External Links ({{ count($scraper->external_links) }}):</strong></p>
                            <ul class="list-disc pl-5">
                                @foreach ($scraper->external_links as $link)
                                <li><a href="{{ $link }}" target="_blank" class="text-blue-400">{{ Str::limit($link, 30)
                                        }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
            <x-textarea />
        </div>
</x-app-layout>
<script>
    // Scrape form submission
    $(document).ready(function() {
        // Scroll to the bottom of chat on page load
        $('#chat-bar').animate({ scrollTop: $('#chat-bar')[0].scrollHeight }, 300);


        $('#scrape-form').on('submit', function(e) {
        e.preventDefault();
        const url = $('#url-input').val();
        if (!url) return;

        // Generate a unique ID for the loader
        const loaderId = `loader-${Date.now()}`;

        // Append user input and loader
        $('#chat-box').append(`
            <div class="flex justify-end chat-entry">
                <div class="bg-[#303030] text-white p-4 rounded-lg max-w-md">
                    ${url}
                </div>
            </div>
            <div class="flex justify-start" id="${loaderId}">
                <div class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-4 rounded-lg max-w-md flex items-center">
                    <span class="loader mr-2"></span> Fetching data...
                </div>
            </div>
        `);

        // Scroll to the bottom
        $('#chat-bar').animate({ scrollTop: $('#chat-bar')[0].scrollHeight }, 300);

        $.ajax({
            url: '{{ route('scraper.scrape') }}',
            method: 'POST',
            data: {
                url: url,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                   //Remove the welcome message
                    $("#welcome-message").fadeOut(300, function () {
                    $(this).remove();
                    });
                 let html = `
                 <div class="flex justify-start chat-entry">
                    <div class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-4 rounded-lg max-w-md">
                        <p> ${response.result}</p>`;

                        if (response.images && Object.keys(response.images).length > 0) {
                            const images = Object.values(response.images); // Convert object to array
                            html += `<p><strong>Images (${images.length}):</strong></p>
                            <div class="flex flex-wrap gap-2">`;
                            images.forEach(image => {
                            html += `<a href="${image}" target="_blank"><img src="${image}" alt="Scraped Image"
                                    class="w-20 h-20 object-cover rounded"></a>`;
                            });
                            html += `</div>`;
                        }

                       if (response.videos && Object.keys(response.videos).length > 0) {
                        const videos = Object.values(response.videos); // Convert object to array
                    html += `<p><strong>Videos (${videos.length}):</strong></p>
                    <ul class="list-disc pl-5 space-y-4">`;
                        videos.forEach(video => {
                        const isEmbedded = video.includes('youtube.com') || video.includes('vimeo.com');
                        let embedUrl = video;
                        if (isEmbedded) {
                        // Simple embed URL generation (can be enhanced)
                        if (video.includes('youtube.com')) {
                        const videoId = video.match(/[?&]v=([^&]+)/)?.[1];
                        embedUrl = videoId ? `https://www.youtube.com/embed/${videoId}` : video;
                        } else if (video.includes('vimeo.com')) {
                        const videoId = video.match(/vimeo\.com\/(\d+)/)?.[1];
                        embedUrl = videoId ? `https://player.vimeo.com/video/${videoId}` : video;
                        }
                        html += `<li><iframe src="${embedUrl}" width="400" height="225" frameborder="0" allowfullscreen
                                class="rounded"></iframe></li>`;
                        } else {
                        // Direct video file
                        const ext = video.split('.').pop();
                        html += `<li><video width="400" controls class="rounded">
                                <source src="${video}" type="video/${ext}">Your browser does not support the video tag.
                            </video></li>`;
                        }
                        });
                        html += `</ul>`;
                    }

                      if (response.external_links && Object.keys(response.external_links).length > 0) {
                    const links = Object.values(response.external_links); // Convert object to array
                    html += `<p><strong>External Links (${links.length}):</strong></p>
                    <ul class="list-disc pl-5">`;
                        links.forEach(link => {
                        html += `<li><a href="${link}" target="_blank" class="text-blue-400">${link.substring(0, 30)}</a></li>`;
                        });
                        html += `</ul>`;
                    }

                        html += `
                    </div>
                </div>`;

                // Replace loader with actual data
                $(`#${loaderId}`).replaceWith(html);
                } else {
                    $(`#${loaderId}`).replaceWith(`<p class="text-red-500">${response.message}</p>`);
                }

                //Scroll to the bottom
                $('#chat-bar').animate({ scrollTop: $('#chat-bar')[0].scrollHeight }, 300);
            },
            error: function(error) {
                $(`#${loaderId}`).replaceWith(`<p class="text-red-500">Error: ${error.responseJSON.message}</p>`);

                //Scroll to the bottom
                $('#chat-bar').animate({ scrollTop: $('#chat-bar')[0].scrollHeight }, 300);
            }
        });

        $('#scrape-form').trigger('reset');
    });
});

</script>
