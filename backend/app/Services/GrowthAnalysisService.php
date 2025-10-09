<?php

namespace App\Services;

use App\Models\Child;
use App\Models\MessageRule;
use Carbon\Carbon;

class GrowthAnalysisService
{
    // WHO 2006 Child Growth Standards (LMS) — usia 6–24 bulan
    // Satuan: berat = kilogram, panjang/tinggi = sentimeter
    private const WHO_REF_MALE = [
        // Weight-for-Age (Boys)
        'wfa' => [
            6  => ['l' => 0.1257, 'm' => 7.9340, 's' => 0.10958],
            7  => ['l' => 0.1134, 'm' => 8.2970, 's' => 0.10902],
            8  => ['l' => 0.1021, 'm' => 8.6151, 's' => 0.10882],
            9  => ['l' => 0.0917, 'm' => 8.9014, 's' => 0.10881],
            10 => ['l' => 0.0820, 'm' => 9.1649, 's' => 0.10891],
            11 => ['l' => 0.0730, 'm' => 9.4122, 's' => 0.10906],
            12 => ['l' => 0.0644, 'm' => 9.6479, 's' => 0.10925],
            13 => ['l' => 0.0563, 'm' => 9.8749, 's' => 0.10949],
            14 => ['l' => 0.0487, 'm' => 10.0953, 's' => 0.10976],
            15 => ['l' => 0.0413, 'm' => 10.3108, 's' => 0.11007],
            16 => ['l' => 0.0343, 'm' => 10.5228, 's' => 0.11041],
            17 => ['l' => 0.0275, 'm' => 10.7319, 's' => 0.11079],
            18 => ['l' => 0.0211, 'm' => 10.9385, 's' => 0.11119],
            19 => ['l' => 0.0148, 'm' => 11.1430, 's' => 0.11164],
            20 => ['l' => 0.0087, 'm' => 11.3462, 's' => 0.11211],
            21 => ['l' => 0.0029, 'm' => 11.5486, 's' => 0.11261],
            22 => ['l' => -0.0028, 'm' => 11.7504, 's' => 0.11314],
            23 => ['l' => -0.0083, 'm' => 11.9514, 's' => 0.11369],
            24 => ['l' => -0.0137, 'm' => 12.1515, 's' => 0.11426],
        ],
        // Length/Height-for-Age (Boys)
        'hfa' => [
            6  => ['l' => 1, 'm' => 67.6236, 's' => 0.03165],
            7  => ['l' => 1, 'm' => 69.1645, 's' => 0.03139],
            8  => ['l' => 1, 'm' => 70.5994, 's' => 0.03124],
            9  => ['l' => 1, 'm' => 71.9687, 's' => 0.03117],
            10 => ['l' => 1, 'm' => 73.2812, 's' => 0.03118],
            11 => ['l' => 1, 'm' => 74.5388, 's' => 0.03125],
            12 => ['l' => 1, 'm' => 75.7488, 's' => 0.03137],
            13 => ['l' => 1, 'm' => 76.9186, 's' => 0.03154],
            14 => ['l' => 1, 'm' => 78.0497, 's' => 0.03174],
            15 => ['l' => 1, 'm' => 79.1458, 's' => 0.03197],
            16 => ['l' => 1, 'm' => 80.2113, 's' => 0.03222],
            17 => ['l' => 1, 'm' => 81.2487, 's' => 0.03250],
            18 => ['l' => 1, 'm' => 82.2587, 's' => 0.03279],
            19 => ['l' => 1, 'm' => 83.2418, 's' => 0.03310],
            20 => ['l' => 1, 'm' => 84.1996, 's' => 0.03342],
            21 => ['l' => 1, 'm' => 85.1348, 's' => 0.03376],
            22 => ['l' => 1, 'm' => 86.0477, 's' => 0.03410],
            23 => ['l' => 1, 'm' => 86.9410, 's' => 0.03445],
            24 => ['l' => 1, 'm' => 87.8161, 's' => 0.03479],
        ],
    ];

    private const WHO_REF_FEMALE = [
        // Weight-for-Age (Girls)
        'wfa' => [
            6  => ['l' => -0.0756, 'm' => 7.2970, 's' => 0.12204],
            7  => ['l' => -0.1039, 'm' => 7.6422, 's' => 0.12178],
            8  => ['l' => -0.1288, 'm' => 7.9487, 's' => 0.12181],
            9  => ['l' => -0.1507, 'm' => 8.2254, 's' => 0.12199],
            10 => ['l' => -0.1700, 'm' => 8.4800, 's' => 0.12223],
            11 => ['l' => -0.1872, 'm' => 8.7192, 's' => 0.12247],
            12 => ['l' => -0.2024, 'm' => 8.9481, 's' => 0.12268],
            13 => ['l' => -0.2158, 'm' => 9.1699, 's' => 0.12283],
            14 => ['l' => -0.2278, 'm' => 9.3870, 's' => 0.12294],
            15 => ['l' => -0.2384, 'm' => 9.6008, 's' => 0.12299],
            16 => ['l' => -0.2478, 'm' => 9.8124, 's' => 0.12303],
            17 => ['l' => -0.2562, 'm' => 10.0226, 's' => 0.12306],
            18 => ['l' => -0.2637, 'm' => 10.2315, 's' => 0.12309],
            19 => ['l' => -0.2703, 'm' => 10.4393, 's' => 0.12315],
            20 => ['l' => -0.2762, 'm' => 10.6464, 's' => 0.12323],
            21 => ['l' => -0.2815, 'm' => 10.8534, 's' => 0.12335],
            22 => ['l' => -0.2862, 'm' => 11.0608, 's' => 0.12350],
            23 => ['l' => -0.2903, 'm' => 11.2688, 's' => 0.12369],
            24 => ['l' => -0.2941, 'm' => 11.4775, 's' => 0.12390],
        ],
        // Length/Height-for-Age (Girls)
        'hfa' => [
            6  => ['l' => 1, 'm' => 65.7311, 's' => 0.03448],
            7  => ['l' => 1, 'm' => 67.2873, 's' => 0.03441],
            8  => ['l' => 1, 'm' => 68.7498, 's' => 0.03440],
            9  => ['l' => 1, 'm' => 70.1435, 's' => 0.03444],
            10 => ['l' => 1, 'm' => 71.4818, 's' => 0.03452],
            11 => ['l' => 1, 'm' => 72.7710, 's' => 0.03464],
            12 => ['l' => 1, 'm' => 74.0150, 's' => 0.03479],
            13 => ['l' => 1, 'm' => 75.2176, 's' => 0.03496],
            14 => ['l' => 1, 'm' => 76.3817, 's' => 0.03514],
            15 => ['l' => 1, 'm' => 77.5099, 's' => 0.03534],
            16 => ['l' => 1, 'm' => 78.6055, 's' => 0.03555],
            17 => ['l' => 1, 'm' => 79.6710, 's' => 0.03576],
            18 => ['l' => 1, 'm' => 80.7079, 's' => 0.03598],
            19 => ['l' => 1, 'm' => 81.7182, 's' => 0.03620],
            20 => ['l' => 1, 'm' => 82.7036, 's' => 0.03643],
            21 => ['l' => 1, 'm' => 83.6654, 's' => 0.03666],
            22 => ['l' => 1, 'm' => 84.6040, 's' => 0.03688],
            23 => ['l' => 1, 'm' => 85.5202, 's' => 0.03711],
            24 => ['l' => 1, 'm' => 86.4153, 's' => 0.03734],
        ],
    ];


    public function analyze(Child $child, float $weightKg, float $lengthCm, string $measuredAt): array
    {
        $birthDate = Carbon::parse($child->birth_date);
        $dateOfVisit = Carbon::parse($measuredAt);
        $ageInMonths = $birthDate->diffInMonths($dateOfVisit);

        // Pilih tabel referensi berdasarkan jenis kelamin
        $refData = ($child->sex === 'M') ? self::WHO_REF_MALE : self::WHO_REF_FEMALE;

        // Ambil data referensi yang paling mendekati usia anak
        $wfaRef = $this->getClosestRef($refData['wfa'], $ageInMonths);
        $hfaRef = $this->getClosestRef($refData['hfa'], $ageInMonths);

        // Hitung Z-Scores
        $zWaz = $this->calculateZScore($weightKg, $wfaRef);
        $zHaz = $this->calculateZScore($lengthCm, $hfaRef);
        // WLZ (Weight-for-Length) perhitungannya lebih kompleks, kita sederhanakan
        $zWlz = $zWaz - $zHaz; // Ini adalah simplifikasi, bukan standar WHO

        // Terjemahkan Z-Scores menjadi Status
        $statusWaz = $this->getScoreStatus($zWaz);
        $statusHaz = $this->getScoreStatus($zHaz);
        $statusWlz = $this->getScoreStatus($zWlz);
        $compositeStatus = $this->getCompositeStatus($statusHaz, $statusWlz);

        // Cari Pesan Saran
        $message = $this->findMatchingMessage($zWaz, $zHaz, $zWlz, $compositeStatus);

        return [
            'z_waz' => round($zWaz, 2),
            'status_waz' => $statusWaz,
            'z_haz' => round($zHaz, 2),
            'status_haz' => $statusHaz,
            'z_wlz' => round($zWlz, 2),
            'status_wlz' => $statusWlz,
            'composite_status' => $compositeStatus,
            'message' => $message['template'] ?? 'Pertumbuhan anak Anda baik! Pertahankan pola makan bergizi seimbang.',
            'message_rule_id' => $message['id'] ?? null,
        ];
    }

    private function calculateZScore(float $value, array $ref): float
    {
        $l = $ref['l'];
        $m = $ref['m'];
        $s = $ref['s'];
        if ($l == 0) return log($value / $m) / $s;
        return (pow($value / $m, $l) - 1) / ($l * $s);
    }

    private function getClosestRef(array $refTable, int $ageInMonths): array
    {
        $closestAge = null;
        foreach (array_keys($refTable) as $age) {
            if ($closestAge === null || abs($ageInMonths - $age) < abs($ageInMonths - $closestAge)) {
                $closestAge = $age;
            }
        }
        return $refTable[$closestAge];
    }

    private function getScoreStatus(float $score): string
    {
        if ($score < -2) return 'low';
        if ($score > 2) return 'high';
        return 'normal';
    }

    private function getCompositeStatus(string $statusHaz, string $statusWlz): string
    {
        if ($statusHaz === 'low') return 'stunted';
        if ($statusWlz === 'low') return 'wasted';
        if ($statusWlz === 'high') return 'overweight';
        return 'normal';
    }

    private function findMatchingMessage(float $zWaz, float $zHaz, float $zWlz, string $compositeStatus)
    {
        $rule = MessageRule::where('is_active', true)
            ->where(function ($query) use ($zWaz, $zHaz, $zWlz, $compositeStatus) {
                $query->where('composite_status_is', $compositeStatus)
                    ->orWhere(function ($q) use ($zWaz, $zHaz, $zWlz) {
                        $q->where(function ($sub) use ($zWaz) {
                            $sub->where('waz_min', '<=', $zWaz)->orWhereNull('waz_min');
                        })->where(function ($sub) use ($zWaz) {
                            $sub->where('waz_max', '>=', $zWaz)->orWhereNull('waz_max');
                        })->where(function ($sub) use ($zHaz) {
                            $sub->where('haz_min', '<=', $zHaz)->orWhereNull('haz_min');
                        })->where(function ($sub) use ($zHaz) {
                            $sub->where('haz_max', '>=', $zHaz)->orWhereNull('haz_max');
                        })->where(function ($sub) use ($zWlz) {
                            $sub->where('wlz_min', '<=', $zWlz)->orWhereNull('wlz_min');
                        })->where(function ($sub) use ($zWlz) {
                            $sub->where('wlz_max', '>=', $zWlz)->orWhereNull('wlz_max');
                        });
                    });
            })
            ->orderBy('priority', 'desc')
            ->first();

        return $rule ? ['id' => $rule->id, 'template' => $rule->message_template] : null;
    }
}
