<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ScraperController extends Controller
{

    public function scraper()
    {
        $client = new Client();
        $urls = collect();
        $links = null;
        $sectionHtml = file_get_contents('https://www.bigbangnews.com/seccion/dinero');
        preg_match_all('/<a href=\".*\">/', $sectionHtml,$links);
        foreach ($links[0] as $link) {
            $url = Str::after($link, '<a href="');
            $url = Str::before($url, '"');
            $urls->push($url);
        }

        $urls = $urls
            ->filter(function ($url) {
                return Str::startsWith($url, '/dinero');
            })
            ->unique();

        $news = collect();
        foreach ($urls as $url) {
            $url = 'https://www.bigbangnews.com/amp' . Str::after($url, '/dinero');
            $newCrawler = $client->request('GET', $url);
            $extraction = collect([
                'sentences' => collect(),
                'url' => $url,
            ]);
            $newCrawler->filter('.newsfull__title')->each(function (Crawler $node) use (&$extraction) {
                $extraction->put('title', $this->cleanText($node->text()));
            });
            $newCrawler->filter('.newsfull__excerpt')->each(function (Crawler $node) use (&$extraction) {
                $extraction->put('excerpt', $this->cleanText($node->text()));
            });
            $newCrawler->filter('.newsfull__body')->each(function (Crawler $node) use (&$extraction) {
                $extraction->put('body', $this->cleanText($node->text()));
            });
            $newCrawler->filter('.newsfull__date')->each(function (Crawler $node) use (&$extraction) {
                $extraction->put('date', $this->cleanText($node->text()));
            });
            $extraction['sentences'] = collect(explode('.', $extraction['body']));
            $news->push($extraction);
        }
        Storage::disk('public')->put('news.json', json_encode($news, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Reemplaza, a través de una regex, los tabuladores y "break lines" por espacios
     * en blancos.
     * @param $text
     *
     * @return string|string[]|null
     */
    public function cleanText($text)
    {
        return preg_replace('/\s+/', ' ', $text);
    }

}
