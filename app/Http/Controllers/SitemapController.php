<?php

namespace App\Http\Controllers;

use App\Modules\Article\Models\Article;
use App\Modules\Page\Models\Page;
use App\Modules\Category\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = $this->generateSitemap();

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    private function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $xml .= $this->addUrl(route('home'), now(), 'daily', '1.0');

        // Articles
        $xml .= $this->addUrl(route('articles.index'), now(), 'daily', '0.9');
        
        $articles = Article::published()->get();
        foreach ($articles as $article) {
            $xml .= $this->addUrl(
                $article->url,
                $article->updated_at,
                'weekly',
                '0.8'
            );
        }

        // Categories
        $xml .= $this->addUrl(route('categories.index'), now(), 'weekly', '0.7');
        
        $categories = Category::active()->get();
        foreach ($categories as $category) {
            $xml .= $this->addUrl(
                $category->url,
                $category->updated_at,
                'weekly',
                '0.7'
            );
        }

        // Pages
        $pages = Page::published()->get();
        foreach ($pages as $page) {
            $xml .= $this->addUrl(
                $page->url,
                $page->updated_at,
                'monthly',
                '0.6'
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function addUrl(string $loc, $lastmod, string $changefreq, string $priority): string
    {
        $xml = '<url>';
        $xml .= '<loc>' . htmlspecialchars($loc) . '</loc>';
        $xml .= '<lastmod>' . $lastmod->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>' . $changefreq . '</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';
        $xml .= '</url>';

        return $xml;
    }
}
