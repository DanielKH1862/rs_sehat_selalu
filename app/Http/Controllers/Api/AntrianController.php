<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    /**
     * Ambil nomor antrian baru
     */
    public function ambil(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'loket_id' => 'required|exists:lokets,id',
        ]);

        // Generate nomor antrian
        $nomorAntrian = Antrian::generateNomorAntrian($validated['loket_id']);

        // Create antrian
        $antrian = Antrian::create([
            'loket_id' => $validated['loket_id'],
            'nomor_antrian' => $nomorAntrian,
            'status' => 'menunggu',
        ]);

        // Load loket relationship
        $antrian->load('loket');

        return response()->json([
            'success' => true,
            'message' => 'Nomor antrian berhasil diambil',
            'data' => $antrian
        ], 201);
    }

    /**
     * Update status antrian
     */
    public function updateStatus(Request $request, Antrian $antrian): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,dipanggil,selesai',
        ]);

        $antrian->status = $validated['status'];
        
        // Set waktu_panggil when status changes to 'dipanggil'
        if ($validated['status'] === 'dipanggil') {
            $antrian->waktu_panggil = now();
        }

        $antrian->save();
        $antrian->load('loket');

        return response()->json([
            'success' => true,
            'message' => 'Status antrian berhasil diperbarui',
            'data' => $antrian
        ]);
    }

    /**
     * Get currently called antrians (all lokets or specific loket)
     */
    public function current(Request $request): JsonResponse
    {
        $query = Antrian::with('loket')
            ->where('status', 'dipanggil')
            ->orderBy('waktu_panggil', 'desc');

        // Optional filter by loket_id
        if ($request->has('loket_id')) {
            $query->where('loket_id', $request->loket_id);
        }

        $antrians = $query->get();

        return response()->json([
            'success' => true,
            'data' => $antrians
        ]);
    }

    /**
     * Get waiting antrians for a specific loket
     */
    public function menunggu(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'loket_id' => 'required|exists:lokets,id',
        ]);

        $antrians = Antrian::with('loket')
            ->where('loket_id', $validated['loket_id'])
            ->where('status', 'menunggu')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $antrians
        ]);
    }

    /**
     * Get antrian history
     */
    public function history(Request $request): JsonResponse
    {
        $query = Antrian::with('loket')
            ->orderBy('created_at', 'desc');

        // Optional filters
        if ($request->has('loket_id')) {
            $query->where('loket_id', $request->loket_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $antrians = $query->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $antrians
        ]);
    }
}
