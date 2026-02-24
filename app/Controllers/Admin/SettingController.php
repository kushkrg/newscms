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

class SettingController
{
    /**
     * Gate: only super admins may access site settings.
     */
    public function middleware(): void
    {
        Auth::requireRole(['super_admin']);
    }

    /**
     * Display all settings grouped by their setting_group column.
     * Email group is excluded — managed in the Subscribers module.
     */
    public function index(Request $request, array $params): void
    {
        $activeTab = $request->get('tab', 'general');

        // Fetch every setting row (excluding email group) so we can group them in PHP.
        $rows = Database::query(
            "SELECT key_name, value, group_name, type, label
             FROM settings
             WHERE group_name NOT IN ('email', 'ads')
             ORDER BY group_name ASC, key_name ASC"
        )->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $group = $row['group_name'] ?: 'general';
            $grouped[$group][] = $row;
        }

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/settings/index', [
            'pageTitle'   => 'Site Settings',
            'currentPage' => 'settings',
            'grouped'     => $grouped,
            'activeTab'   => $activeTab,
        ]);
    }

    /**
     * Persist the submitted settings.
     *
     * Expects POST data in the shape: settings[key] = value.
     * Falls back to iterating all POST keys (excluding the CSRF token) when
     * the nestled format is not used.
     */
    public function save(Request $request, array $params): void
    {
        Csrf::check();

        $settings = $request->post('settings');

        if (is_array($settings)) {
            // Nested format: settings[site_name] = "My Blog"
            foreach ($settings as $key => $value) {
                Setting::set((string) $key, (string) $value);
            }
        } else {
            // Flat format: every POST field is a setting (except CSRF token)
            $all = $_POST;
            unset($all['_csrf_token']);
            foreach ($all as $key => $value) {
                Setting::set((string) $key, (string) $value);
            }
        }

        Session::flash('success', 'Settings saved successfully.');

        $tab = $request->post('active_tab') ?: 'general';
        Response::redirect(url('admin/settings?tab=' . $tab));
    }
}
