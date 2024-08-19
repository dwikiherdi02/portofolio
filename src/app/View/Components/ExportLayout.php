<?php

namespace App\View\Components;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ExportLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // $date = date('d').' '.$this->months(date('n')).' '.date('Y');
        $date = Carbon::now()->translatedFormat('l d F Y');
        $logo = storage_path('app/private/surat/logo.png');
        return view('layouts.export', compact('date', 'logo'));
    }

    protected function months($month): string {
        $months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        return $months[$month-1];
    }
}
