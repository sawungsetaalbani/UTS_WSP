<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // GET /api/books — Ambil semua buku
    public function index(): JsonResponse
    {
        $books = Book::all();

        return response()->json([
            'status'  => true,
            'message' => 'Data buku berhasil diambil',
            'data'    => $books,
        ]);
    }

    // GET /api/books/{id} — Ambil satu buku
    public function show(int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Data buku berhasil diambil',
            'data'    => $book,
        ]);
    }

    // POST /api/books — Tambah buku baru
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required|string',
            'author' => 'required|string',
            'year'   => 'required|integer|min:1900',
            'stock'  => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'data'    => $validator->errors(),
            ], 422);
        }

        $book = Book::create($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $book,
        ], 201);
    }

    // PUT /api/books/{id} — Update buku
    public function update(Request $request, int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'  => 'required|string',
            'author' => 'required|string',
            'year'   => 'required|integer|min:1900',
            'stock'  => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'data'    => $validator->errors(),
            ], 422);
        }

        $book->update($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Buku berhasil diperbarui',
            'data'    => $book,
        ]);
    }

    // DELETE /api/books/{id} — Hapus buku
    public function destroy(int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        $book->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Buku berhasil dihapus',
            'data'    => null,
        ]);
    }
}