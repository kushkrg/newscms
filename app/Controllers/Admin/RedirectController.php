<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Models\Redirect;

class RedirectController
{
    /**
     * Run before every action -- require super_admin or editor role.
     */
    public function middleware(): void
    {
        Auth::requireRole(['super_admin', 'editor']);
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/redirects
     * ----------------------------------------------------------------*/

    public function index(Request $request, array $params): void
    {
        $redirects = Redirect::all();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/redirects/index', [
            'pageTitle'   => 'Redirects',
            'currentPage' => 'redirects',
            'redirects'  => $redirects,
        ]);
    }

    /* ------------------------------------------------------------------
     *  STORE  --  POST /admin/redirects
     * ----------------------------------------------------------------*/

    public function store(Request $request, array $params): void
    {
        Csrf::check();

        $data = [
            'from_path'   => trim((string) $request->post('from_path', '')),
            'to_url'      => trim((string) $request->post('to_url', '')),
            'type' => (int) $request->post('type', 301),
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'from_path' => ['required'],
            'to_url'    => ['required'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', $data);
            Response::redirect(url('admin/redirects'));
        }

        // Ensure from_path starts with a /
        if (!str_starts_with($data['from_path'], '/')) {
            $data['from_path'] = '/' . $data['from_path'];
        }

        // Validate type is a valid redirect code
        if (!in_array($data['type'], [301, 302, 307, 308], true)) {
            $data['type'] = 301;
        }

        $redirectData = [
            'from_path' => $data['from_path'],
            'to_url'    => $data['to_url'],
            'type'      => $data['type'],
        ];

        Redirect::create($redirectData);

        Session::flash('success', 'Redirect created successfully.');
        Response::redirect(url('admin/redirects'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/redirects/{id}/delete
     * ----------------------------------------------------------------*/

    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $redirectId = (int) $params['id'];

        Redirect::delete($redirectId);

        Session::flash('success', 'Redirect deleted successfully.');
        Response::redirect(url('admin/redirects'));
    }
}
