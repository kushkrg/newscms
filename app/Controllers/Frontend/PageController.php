<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Request;
use App\Core\Response;
use App\Core\Sanitizer;
use App\Core\SEO;
use App\Core\View;
use App\Models\Page;

class PageController
{
    /**
     * Display a single published static page by its slug.
     */
    public function show(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $page = Page::findBySlug($slug);

        if (!$page || ($page['status'] ?? '') !== 'published') {
            Response::notFound();
            return;
        }

        // SEO
        $description = !empty($page['meta_description'])
            ? $page['meta_description']
            : Sanitizer::excerpt($page['content'] ?? '', 160);

        $seo = new SEO();
        $seo->setTitle($page['title'])
            ->setDescription($description)
            ->setCanonical(url($page['slug']));

        $seo->setBreadcrumbs([
            ['name' => 'Home',          'url' => url('/')],
            ['name' => $page['title'],  'url' => url($page['slug'])],
        ]);

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/page', [
            'pageTitle' => $page['title'],
            'page'      => $page,
            'seo'       => $seo,
        ]);
    }
}
