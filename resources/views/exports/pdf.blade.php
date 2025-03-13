<!DOCTYPE html>
<html>

<head>
    <title>Scraper Export</title>
</head>

<body>
    <h1>Scraper Data</h1>
    <p><strong>URL:</strong> {{ $scraper->url }}</p>
    <p><strong>Result:</strong> {{ $scraper->result }}</p>
    <p><strong>Images:</strong> {{ is_array($scraper->images) ? implode(', ', $scraper->images) : $scraper->images }}
    </p>
    <p><strong>Videos:</strong> {{ is_array($scraper->videos) ? implode(', ', $scraper->videos) : $scraper->videos }}
    </p>
    <p><strong>External Links:</strong> {{ is_array($scraper->external_links) ? implode(', ', $scraper->external_links)
        : $scraper->external_links
        }}</p>
    <p><strong>date created:</strong> {{ $scraper->created_at }}</p>

</body>

</html>