<?php

namespace App\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GlobalComposer
{
    /**
     * Create a new profile composer.
     */
    public function __construct(
        protected Request $request
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('notifSuccess', $this->request->session()->get('notif-success',''));
        $view->with('notifDanger', $this->request->session()->get('notif-danger',''));
    }
}