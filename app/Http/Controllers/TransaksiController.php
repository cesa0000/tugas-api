<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Read All: hanya admin. Data relasi user dan genre ikut diambil.
     */
    public function index(): JsonResponse
    {
        $transaksis = Transaksi::with(['user:id,name,email', 'genre'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi berhasil diambil',
            'data'    => $transaksis,
        ], 200);
    }

    /**
     * Create: customer yang sudah login.
     * user_id diambil dari akun yang login, bukan dari input.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'genre_id' => 'required|exists:genres,id',
            'jumlah'   => 'required|integer|min:1',
            'catatan'  => 'nullable|string',
        ]);

        $transaksi = Transaksi::create([
            'user_id'  => $request->user()->id,
            'genre_id' => $validated['genre_id'],
            'jumlah'   => $validated['jumlah'],
            'catatan'  => $validated['catatan'] ?? null,
        ]);

        $transaksi->load(['user:id,name,email', 'genre']);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'data'    => $transaksi,
        ], 201);
    }

    /**
     * Show: customer yang sudah login, hanya untuk transaksi miliknya.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $transaksi = Transaksi::with(['user:id,name,email', 'genre'])->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        if ($transaksi->user_id != $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak berhak mengakses transaksi ini',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil diambil',
            'data'    => $transaksi,
        ], 200);
    }

    /**
     * Update: customer yang sudah login, hanya untuk transaksi miliknya.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        if ($transaksi->user_id != $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak berhak mengubah transaksi ini',
            ], 403);
        }

        $validated = $request->validate([
            'genre_id' => 'required|exists:genres,id',
            'jumlah'   => 'required|integer|min:1',
            'catatan'  => 'nullable|string',
        ]);

        $transaksi->update($validated);
        $transaksi->load(['user:id,name,email', 'genre']);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diperbarui',
            'data'    => $transaksi,
        ], 200);
    }

    /**
     * Destroy: hanya admin.
     */
    public function destroy(string $id): JsonResponse
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        $transaksi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus',
        ], 200);
    }
}