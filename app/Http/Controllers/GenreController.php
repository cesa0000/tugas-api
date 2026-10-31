<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Daftar genre berhasil diambil',
            'data'    => Genre::all(),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:genres,name',
            'description' => 'nullable|string',
        ]);

        $genre = Genre::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Genre berhasil ditambahkan',
            'data'    => $genre,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail genre berhasil diambil',
            'data'    => $genre,
        ], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string',
        ]);

        $genre->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Genre berhasil diperbarui',
            'data'    => $genre,
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        $genre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Genre berhasil dihapus',
        ], 200);
    }
}