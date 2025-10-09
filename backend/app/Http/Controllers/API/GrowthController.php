<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Services\GrowthAnalysisService; 
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GrowthController extends Controller
{
    protected $analysisService;

    // 2. "Suntikkan" service ke dalam controller saat ia dibuat
    public function __construct(GrowthAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }

    /**
     * Menyimpan catatan pertumbuhan baru dan mentrigger analisis.
     */
    public function store(Request $request, Child $child)
    {
        // Keamanan: Pastikan user hanya bisa menambah data untuk anaknya sendiri
        if ($child->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Validasi Input
        $validated = $request->validate([
            'weight_kg' => 'required|numeric|min:1|max:50',
            'length_cm' => 'required|numeric|min:30|max:150',
            'measured_at' => 'required|date|before_or_equal:today',
        ]);

        // 3. Gunakan DB::transaction untuk keamanan data
        $analysisResult = DB::transaction(function () use ($child, $validated) {
            // 3a. Simpan data pengukuran mentah ke tabel `growth_records`
            $growthRecord = $child->growthRecords()->create([
                'weight_kg' => $validated['weight_kg'],
                'length_cm' => $validated['length_cm'],
                'measured_at' => $validated['measured_at'],
            ]);

            // 3b. Panggil Service untuk melakukan analisis cerdas
            $analysis = $this->analysisService->analyze(
                $child,
                $validated['weight_kg'],
                $validated['length_cm'],
                $validated['measured_at']
            );

            // 3c. Simpan hasil analisis dari Service ke tabel `growth_assessments`
            $assessment = $growthRecord->assessment()->create([
                'child_id' => $child->id,
                'assessed_at' => Carbon::now(),
                'z_waz' => $analysis['z_waz'],
                'z_haz' => $analysis['z_haz'],
                'z_wlz' => $analysis['z_wlz'],
                'status_waz' => $analysis['status_waz'],
                'status_haz' => $analysis['status_haz'],
                'status_wlz' => $analysis['status_wlz'],
                'composite_status' => $analysis['composite_status'],
            ]);

            // 3d. Simpan pesan saran dari Service ke tabel `insight_messages`
            if (isset($analysis['message'])) {
                $assessment->insightMessage()->create([
                    'child_id' => $child->id,
                    'message_rule_id' => $analysis['message_rule_id'],
                    'message' => $analysis['message'],
                ]);
            }
            
            // 3e. Update status terakhir di profil anak untuk akses cepat
            $child->update(['last_assessed_at' => Carbon::now()]);

            return $analysis;
        });

        // 4. Kembalikan respons JSON berisi hasil analisis lengkap ke aplikasi
        return response()->json($analysisResult, 201);
    }
}