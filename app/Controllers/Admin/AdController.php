<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Setting;

class AdController
{
    /**
     * Gate: only super admins may manage ads.
     */
    public function middleware(): void
    {
        Auth::requireRole(['super_admin']);
    }

    /**
     * Display the ad management page.
     */
    public function index(Request $request, array $params): void
    {
        $ads = Setting::getByGroup('ads');

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/ads/index', [
            'pageTitle'   => 'Google Ads Management',
            'currentPage' => 'ads',
            'ads'         => $ads,
        ]);
    }

    /**
     * Save ad settings.
     */
    public function save(Request $request, array $params): void
    {
        Csrf::check();

        $settings = $request->post('settings');

        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                Setting::set((string) $key, (string) $value);
            }
        }

        // Handle the toggle (checkbox sends '1' only if checked)
        if (!isset($settings['ads_enabled'])) {
            Setting::set('ads_enabled', '0');
        }

        Session::flash('success', 'Ad settings saved successfully.');
        Response::redirect(url('admin/ads'));
    }
}
