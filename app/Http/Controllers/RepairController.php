<?php

namespace App\Http\Controllers;

use App\Models\RepairDevice;
use Illuminate\View\View;

class RepairController extends Controller
{
    /**
     * Repair overview: one entry per section with its published devices.
     */
    public function index(): View
    {
        $devicesByFamily = RepairDevice::query()->published()->get()->groupBy('family');

        return view('pages.reparation', compact('devicesByFamily'));
    }

    /**
     * One section page (instruments, consoles, other devices) with its devices and their repairs.
     */
    public function show(string $family): View
    {
        $devices = RepairDevice::query()->published()->where('family', $family)->with('services')->get();

        return view('pages.reparation-family', compact('family', 'devices'));
    }
}
