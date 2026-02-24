<?php

namespace App\Core;

class SEO
{
    private array $meta = [];
    private array $breadcrumbs = [];
    private ?array $structuredData = null;
    private ?string $prevUrl = null;
    private ?string $nextUrl = null;

    public function setTitle(string $title, bool $appendSiteName = true): self
    {
        $siteName = $this->getSiteName();
        $this->meta['title'] = $appendSiteName ? "$title - $siteName" : $title;
        $this->meta['og_title'] = $title;
        return $this;
    }

    public function setDescription(string $desc): self
    {
        $desc = mb_substr(strip_tags($desc), 0, 320);
        $this->meta['description'] = $desc;
        $this->meta['og_description'] = $desc;
        return $this;
    }

    public function setCanonical(string $url): self
    {
        $this->meta['canonical'] = $url;
        $this->meta['og_url'] = $url;
        return $this;
    }

    public function setImage(string $url): self
    {
        $this->meta['og_image'] = $url;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->meta['og_type'] = $type;
        return $this;
    }

    public function setArticleMeta(string $publishedAt, string $modifiedAt, string $authorUrl, string $section, array $tags = []): self
    {
        $this->meta['article_published'] = $publishedAt;
        $this->meta['article_modified'] = $modifiedAt;
        $this->meta['article_author'] = $authorUrl;
        $this->meta['article_section'] = $section;
        $this->meta['article_tags'] = $tags;
        return $this;
    }

    public function setRobots(string $value): self
    {
        $this->meta['robots'] = $value;
        return $this;
    }

    public function setPagination(?string $prev, ?string $next): self
    {
        $this->prevUrl = $prev;
        $this->nextUrl = $next;
        return $this;
    }

    public function setBreadcrumbs(array $items): self
    {
        $this->breadcrumbs = $items;
        return $this;
    }

    public function setStructuredData(array $data): self
    {
        $this->structuredData = $data;
        return $this;
    }

    public function renderHead(): string
    {
        $html = '';
        $siteName = $this->getSiteName();

        // Title
        $title = $this->meta['title'] ?? $siteName;
        $html .= "<title>" . h($title) . "</title>\n";

        // Description
        if (!empty($this->meta['description'])) {
            $html .= '<meta name="description" content="' . h($this->meta['description']) . '">' . "\n";
        }

        // Robots
        $robots = $this->meta['robots'] ?? 'index, follow';
        $html .= '<meta name="robots" content="' . h($robots) . '">' . "\n";

        // Canonical
        if (!empty($this->meta['canonical'])) {
            $html .= '<link rel="canonical" href="' . h($this->meta['canonical']) . '">' . "\n";
        }

        // Open Graph
        $html .= '<meta property="og:type" content="' . h($this->meta['og_type'] ?? 'website') . '">' . "\n";
        $html .= '<meta property="og:title" content="' . h($this->meta['og_title'] ?? $title) . '">' . "\n";
        $html .= '<meta property="og:site_name" content="' . h($siteName) . '">' . "\n";

        if (!empty($this->meta['og_description'])) {
            $html .= '<meta property="og:description" content="' . h($this->meta['og_description']) . '">' . "\n";
        }
        if (!empty($this->meta['og_url'])) {
            $html .= '<meta property="og:url" content="' . h($this->meta['og_url']) . '">' . "\n";
        }
        if (!empty($this->meta['og_image'])) {
            $html .= '<meta property="og:image" content="' . h($this->meta['og_image']) . '">' . "\n";
        }

        // Twitter Card
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . h($this->meta['og_title'] ?? $title) . '">' . "\n";
        if (!empty($this->meta['og_description'])) {
            $html .= '<meta name="twitter:description" content="' . h($this->meta['og_description']) . '">' . "\n";
        }
        if (!empty($this->meta['og_image'])) {
            $html .= '<meta name="twitter:image" content="' . h($this->meta['og_image']) . '">' . "\n";
        }

        // Article meta
        if (!empty($this->meta['article_published'])) {
            $html .= '<meta property="article:published_time" content="' . h($this->meta['article_published']) . '">' . "\n";
        }
        if (!empty($this->meta['article_modified'])) {
            $html .= '<meta property="article:modified_time" content="' . h($this->meta['article_modified']) . '">' . "\n";
        }
        if (!empty($this->meta['article_author'])) {
            $html .= '<meta property="article:author" content="' . h($this->meta['article_author']) . '">' . "\n";
        }
        if (!empty($this->meta['article_section'])) {
            $html .= '<meta property="article:section" content="' . h($this->meta['article_section']) . '">' . "\n";
        }
        if (!empty($this->meta['article_tags'])) {
            foreach ($this->meta['article_tags'] as $tag) {
                $html .= '<meta property="article:tag" content="' . h($tag) . '">' . "\n";
            }
        }

        // Pagination
        if ($this->prevUrl) {
            $html .= '<link rel="prev" href="' . h($this->prevUrl) . '">' . "\n";
        }
        if ($this->nextUrl) {
            $html .= '<link rel="next" href="' . h($this->nextUrl) . '">' . "\n";
        }

        // RSS
        $html .= '<link rel="alternate" type="application/rss+xml" title="' . h($siteName) . ' RSS" href="' . url('feed') . '">' . "\n";

        // Structured Data
        if ($this->structuredData) {
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode($this->structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            $html .= "\n</script>\n";
        }

        // Breadcrumb structured data
        if (!empty($this->breadcrumbs)) {
            $items = [];
            foreach ($this->breadcrumbs as $i => $crumb) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $crumb['name'],
                    'item' => $crumb['url'],
                ];
            }
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $items,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            $html .= "\n</script>\n";
        }

        return $html;
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    private function getSiteName(): string
    {
        try {
            $stmt = Database::query("SELECT value FROM settings WHERE key_name = 'site_name' LIMIT 1");
            $row = $stmt->fetch();
            return $row ? $row['value'] : env('APP_NAME', 'NewsCMS');
        } catch (\Exception $e) {
            return env('APP_NAME', 'NewsCMS');
        }
    }

    // Structured data builders
    public static function websiteSchema(): array
    {
        $baseUrl = env('APP_URL', '');
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => env('APP_NAME', 'NewsCMS'),
            'url' => $baseUrl,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $baseUrl . '/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public static function articleSchema(array $post, array $author): array
    {
        $baseUrl = env('APP_URL', '');
        $data = [
            '@context' => 'https://schema.org',
            '@type' => $post['schema_type'] ?? 'Article',
            'headline' => $post['title'],
            'description' => $post['excerpt'] ?? '',
            'author' => [
                '@type' => 'Person',
                'name' => $author['name'],
                'url' => $baseUrl . '/author/' . $author['slug'],
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => env('APP_NAME', 'NewsCMS'),
            ],
            'datePublished' => date('c', strtotime($post['published_at'])),
            'dateModified' => date('c', strtotime($post['updated_at'])),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $baseUrl . '/' . $post['slug'],
            ],
        ];

        if (!empty($post['featured_image'])) {
            $data['image'] = upload_url($post['featured_image']);
        }

        return $data;
    }
}
