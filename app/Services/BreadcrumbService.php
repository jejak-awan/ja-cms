<?php

namespace App\Services;

class BreadcrumbService
{
    protected array $items = [];

    public function add(string $title, ?string $url = null): self
    {
        $this->items[] = [
            'title' => $title,
            'url' => $url,
            'active' => false,
        ];

        return $this;
    }

    public function addHome(string $title = 'Home', ?string $url = null): self
    {
        return $this->add($title, $url ?? route('home'));
    }

    public function addCurrent(string $title): self
    {
        $this->items[] = [
            'title' => $title,
            'url' => null,
            'active' => true,
        ];

        return $this;
    }

    public function get(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function render(string $separator = ' › ', string $class = 'breadcrumb'): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $html = '<nav class="' . $class . '" aria-label="Breadcrumb"><ol>';
        
        foreach ($this->items as $item) {
            $html .= '<li>';
            
            if ($item['url'] && !$item['active']) {
                $html .= '<a href="' . $item['url'] . '">' . $item['title'] . '</a>';
                $html .= ' ' . $separator . ' ';
            } else {
                $html .= '<span>' . $item['title'] . '</span>';
            }
            
            $html .= '</li>';
        }
        
        $html .= '</ol></nav>';
        
        return $html;
    }

    public function renderJson(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $items = [];
        foreach ($this->items as $index => $item) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['title'],
                'item' => $item['url'] ?? null,
            ];
        }

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];

        return json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public static function make(): self
    {
        return new self();
    }

    public static function forArticle(\App\Modules\Article\Models\Article $article): array
    {
        $breadcrumb = self::make()->addHome();

        if ($article->category) {
            $ancestors = $article->category->ancestors()->reverse();
            foreach ($ancestors as $ancestor) {
                $breadcrumb->add($ancestor->name, $ancestor->url);
            }
            $breadcrumb->add($article->category->name, $article->category->url);
        }

        $breadcrumb->addCurrent($article->title);

        return $breadcrumb->get();
    }

    public static function forCategory(\App\Modules\Category\Models\Category $category): array
    {
        $breadcrumb = self::make()->addHome();

        $ancestors = $category->ancestors()->reverse();
        foreach ($ancestors as $ancestor) {
            $breadcrumb->add($ancestor->name, $ancestor->url);
        }

        $breadcrumb->addCurrent($category->name);

        return $breadcrumb->get();
    }

    public static function forPage(\App\Modules\Page\Models\Page $page): array
    {
        $breadcrumb = self::make()->addHome();

        $ancestors = $page->ancestors()->reverse();
        foreach ($ancestors as $ancestor) {
            $breadcrumb->add($ancestor->title, $ancestor->url);
        }

        $breadcrumb->addCurrent($page->title);

        return $breadcrumb->get();
    }

    public static function forSearch(string $query): array
    {
        return self::make()
            ->addHome()
            ->addCurrent("Search: {$query}")
            ->get();
    }
}
