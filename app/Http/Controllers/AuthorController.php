<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Daftar author berhasil diambil',
            'data'    => Author::all(),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $author = Author::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil ditambahkan',
            'data'    => $author,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail author berhasil diambil',
            'data'    => $author,
        ], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $author->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil diperbarui',
            'data'    => $author,
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author dengan ID ' . $id . ' tidak ditemukan',
            ], 404);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil dihapus',
        ], 200);
    }
}