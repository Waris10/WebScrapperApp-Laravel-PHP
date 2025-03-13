<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Scraper;
use Illuminate\Http\Request;
use App\Events\ScraperCreated;
use App\Events\ScraperDeleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Symfony\Component\DomCrawler\Crawler;

class ScraperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::id();
        $scrapers = Scraper::where('user_id', $user_id)->select('id', 'url', 'created_at')->latest()->get();
        $now = Carbon::now();

        //Grouping the scrapers history per time periods
        $groupedScrapersHistories = [
            'today' => [],
            'yesterday' => [],
            'previous_7_days' => [],
            'previous_30_days' => [],
        ];

        foreach ($scrapers as $scraper) {
            $createdAt = Carbon::parse($scraper->created_at);

            if ($createdAt->isToday()) {
                $groupedScrapersHistories['today'][] = $scraper;
            } elseif ($createdAt->isYesterday()) {
                $groupedScrapersHistories['yesterday'][] = $scraper;
            } elseif ($createdAt->between($now->subDays(7), $now->subDays(1))) {
                $groupedScrapersHistories['previous_7_days'][] = $scraper;
            } elseif ($createdAt->between($now->subDays(30), $now->subDays(7))) {
                $groupedScrapersHistories['previous_30_days'][] = $scraper;
            }
        }
        //  dd($groupedScrapersHistories['today']);
        return view('chat', compact('groupedScrapersHistories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function scrape(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->url;
        $user_id = Auth::id();
        $client = new Client();
        $crawler = new Crawler();

        try {
            $response = $client->get($url);
            $crawler->addHtmlContent($response->getBody()->getContents());

            //Scraping all paragraph texts
            $items = $crawler->filter('h1, h2, h3, h4, h5, h6,  p')->each(function (Crawler $node) {
                return $node->text();
            });
            $result = count($items) > 0 ? implode("\n", $items) : "No data found";

            //Scraping images
            $images = $crawler->filter('img')->each(function (Crawler $node) use ($url) {
                $src =  $node->attr('src');
                // Convert relative URLs to absolute URLs
                return $this->makeAbsoluteUrl($src, $url);
            });
            $images = array_filter($images); // Remove empty/null entries

            //Scraping Videos
            // Scrape videos (<video> tags or iframes for embedded videos)
            $videos = $crawler->filter('video source, iframe')->each(function (Crawler $node) use ($url) {
                if ($node->nodeName() === 'source') {
                    $src =  $node->attr('src');
                    return $this->makeAbsoluteUrl($src, $url);
                } elseif ($node->nodeName() === 'iframe') {
                    $src =  $node->attr('src');
                    return $this->makeAbsoluteUrl($src, $url);
                }

                return null;
            });
            $videos = array_filter($videos); // Remove empty/null entries

            // Scrape external links
            $externalLinks = $crawler->filter('a[href]')->each(function (Crawler $node) use ($url) {
                $href =  $node->attr('href');
                $absoluteUrl = $this->makeAbsoluteUrl($href, $url);

                //Checking if the link is an external link by comparing domains
                $baseDomain = parse_url($url, PHP_URL_HOST);
                $linkDomain = parse_url($absoluteUrl, PHP_URL_HOST);
                return ($linkDomain && $linkDomain !== $baseDomain) ? $absoluteUrl : null;
            });
            $externalLinks = array_filter($externalLinks); // Remove empty/null entries


            //Save to the scraper table
            $scraper = Scraper::create([
                "user_id" => $user_id,
                "url" => $url,
                "result" => $result,
                "images" => $images,
                "videos" => $videos,
                "external_links" => $externalLinks
            ]);

            event(new ScraperCreated($scraper));
            return response()->json([
                'success' => true,
                'result' => $result,
                'items_count' => count($items),
                'scraper_id' => $scraper->id,
                'images' => $images,
                'videos' => $videos,
                'external_links' => $externalLinks,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error scraping: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $scraper = Scraper::where('user_id', Auth::id())->findOrFail($id);
        $scraper->delete();

        event(new ScraperDeleted($id));
        return response()->json([
            'success' => true,
            'message' => 'Scraper deleted successfully'
        ]);
    }

    private function makeAbsoluteUrl($url, $baseURL)
    {
        if (!$url) {
            return null;
        }

        if (parse_url($url, PHP_URL_SCHEME) !== null) {
            return $url;
        }

        $base = parse_url($baseURL);
        $scheme = $base['scheme'] ?? 'http';
        $host = $base['host'] ?? '';
        $path = $base['path'] ?? '';
        if (str_starts_with($url, '/')) {
            return "$scheme://$host$url";
        }

        $basePath = dirname($path) === '/' ? '' : dirname($path);
        return "$scheme://$host$basePath/$url";
    }
}