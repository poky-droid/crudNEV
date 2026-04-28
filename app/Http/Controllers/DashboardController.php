<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\News;
use App\Models\Modul;
use App\Models\Sponsor;
use App\Models\Divisi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'title' => 'Dashboard',
            'counts' => [
                'anggota' => Anggota::count(),
                'news'    => News::count(),
                'modul'   => Modul::count(),
                'sponsor' => Sponsor::count(),
                'divisi'  => Divisi::count(),
            ],
            'latestAnggota' => Anggota::with(['divisi','jabatan'])->latest()->take(5)->get(),
        ]);
    }
}
