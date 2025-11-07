<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoketController extends Controller
{
    /**
     * Display a listing of lokets
     */
    public function index(): JsonResponse
    {
        $lokets = Loket::all();
        
        return response()->json([
            'success' => true,
            'data' => $lokets
        ]);
    }

    /**
     * Store a newly created loket
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_loket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $loket = Loket::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Loket berhasil ditambahkan',
            'data' => $loket
        ], 201);
    }

    /**
     * Display the specified loket
     */
    public function show(Loket $loket): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $loket
        ]);
    }

    /**
     * Update the specified loket
     */
    public function update(Request $request, Loket $loket): JsonResponse
    {
        $validated = $request->validate([
            'nama_loket' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $loket->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Loket berhasil diperbarui',
            'data' => $loket
        ]);
    }

    /**
     * Remove the specified loket
     */
    public function destroy(Loket $loket): JsonResponse
    {
        $loket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loket berhasil dihapus'
        ]);
    }
}
