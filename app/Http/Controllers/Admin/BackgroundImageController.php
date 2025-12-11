<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackgroundImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackgroundImageController extends Controller
{
    /**
     * Näita piltide haldust (nimekiri + üleslaadimine).
     */
    public function index()
    {
        $backgrounds = BackgroundImage::orderByDesc('is_active')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.backgrounds.index', compact('backgrounds'));
    }

    /**
     * Salvesta uus pilt.
     * ÜKS PILT saab olla korraga aktiivne – kui "aktiivne" märgitud,
     * siis muudame teised mitteaktiivseks.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image'    => 'required|image|max:4096', // kuni 4MB
            'is_active'=> 'nullable|boolean',
        ]);

        // Laeme pildi public diskile (storage/app/public/backgrounds)
        $path = $request->file('image')->store('backgrounds', 'public');

        $background = new BackgroundImage();
        $background->file_path = $path;
        $background->is_active = false;

        // Kui märgitud "tee aktiivseks"
        if ($request->boolean('is_active')) {
            // Paneme kõik varasemad mitteaktiivseks
            BackgroundImage::query()->update(['is_active' => false]);
            $background->is_active = true;
        }

        $background->save();

        return redirect()
            ->route('backgrounds.index')
            ->with('success', 'Taustapilt lisatud.');
    }

       /**
     * Lülita pildi aktiivsust:
     *  - kui praegu on aktiivne -> tehakse mitteaktiivseks
     *  - kui praegu on mitteaktiivne -> tehakse aktiivseks ja kõik teised mitteaktiivseks
     */
    public function activate(BackgroundImage $background)
    {
        if ($background->is_active) {
            // Oli aktiivne → muuda mitteaktiivseks
            $background->is_active = false;
            $background->save();
            $message = 'Taustapilt märgiti mitteaktiivseks.';
        } else {
            // Oli mitteaktiivne → enne kõik teised mitteaktiivseks
            BackgroundImage::query()->update(['is_active' => false]);

            // ning see pilt aktiivseks
            $background->is_active = true;
            $background->save();
            $message = 'Taustapilt märgiti aktiivseks.';
        }

        return redirect()
            ->route('backgrounds.index')
            ->with('success', $message);
    }
    /**
     * Kustuta pilt (fail + kirje).
     */
    public function destroy(BackgroundImage $background)
    {
        // kustuta fail kettalt
        if ($background->file_path && Storage::disk('public')->exists($background->file_path)) {
            Storage::disk('public')->delete($background->file_path);
        }

        $background->delete();

        return redirect()
            ->route('backgrounds.index')
            ->with('success', 'Taustapilt kustutatud.');
    }
}
