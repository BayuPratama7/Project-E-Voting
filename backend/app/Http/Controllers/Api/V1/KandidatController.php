<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Kandidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KandidatController extends Controller
{
    /**
     * Display a listing of the kandidat.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $status = $request->get('status');
        $posisi = $request->get('posisi');

        $query = Kandidat::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($posisi) {
            $query->where('posisi', $posisi);
        }

        $kandidat = $query->orderBy('vote_count', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $kandidat
        ], 200);
    }

    /**
     * Store a newly created kandidat.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:kandidat,nim',
            'email' => 'required|email|unique:kandidat,email',
            'kelas' => 'required|string|max:50',
            'semester' => 'required|string|max:10',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'posisi' => 'required|in:ketua,wakil_ketua,sekretaris,bendahara,anggota',
            'status' => 'sometimes|in:active,inactive',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $kandidatData = $request->only([
            'nama', 'nim', 'email', 'kelas', 'semester', 
            'visi', 'misi', 'posisi'
        ]);

        $kandidatData['status'] = $request->get('status', 'active');

        // Handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/kandidat', $filename);
            $kandidatData['foto'] = $filename;
        }

        $kandidat = Kandidat::create($kandidatData);

        return response()->json([
            'status' => 'success',
            'message' => 'Kandidat created successfully',
            'data' => $kandidat
        ], 201);
    }

    /**
     * Display the specified kandidat.
     */
    public function show($id)
    {
        $kandidat = Kandidat::with(['votes.pemilih'])->find($id);

        if (!$kandidat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kandidat not found'
            ], 404);
        }

        $kandidat->foto_url = $kandidat->foto_url;

        return response()->json([
            'status' => 'success',
            'data' => $kandidat
        ], 200);
    }

    /**
     * Update the specified kandidat.
     */
    public function update(Request $request, $id)
    {
        $kandidat = Kandidat::find($id);

        if (!$kandidat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kandidat not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'nim' => 'sometimes|string|unique:kandidat,nim,' . $id,
            'email' => 'sometimes|email|unique:kandidat,email,' . $id,
            'kelas' => 'sometimes|string|max:50',
            'semester' => 'sometimes|string|max:10',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'posisi' => 'sometimes|in:ketua,wakil_ketua,sekretaris,bendahara,anggota',
            'status' => 'sometimes|in:active,inactive',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only([
            'nama', 'nim', 'email', 'kelas', 'semester', 
            'visi', 'misi', 'posisi', 'status'
        ]);

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($kandidat->foto) {
                Storage::delete('public/kandidat/' . $kandidat->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/kandidat', $filename);
            $updateData['foto'] = $filename;
        }

        $kandidat->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Kandidat updated successfully',
            'data' => $kandidat->fresh()
        ], 200);
    }

    /**
     * Remove the specified kandidat.
     */
    public function destroy($id)
    {
        $kandidat = Kandidat::find($id);

        if (!$kandidat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kandidat not found'
            ], 404);
        }

        // Check if kandidat has votes
        if ($kandidat->votes()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete kandidat who has received votes'
            ], 400);
        }

        // Delete foto if exists
        if ($kandidat->foto) {
            Storage::delete('public/kandidat/' . $kandidat->foto);
        }

        $kandidat->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kandidat deleted successfully'
        ], 200);
    }

    /**
     * Get kandidat statistics.
     */
    public function statistics()
    {
        $total = Kandidat::count();
        $active = Kandidat::active()->count();
        $byPosisi = Kandidat::groupBy('posisi')
                           ->selectRaw('posisi, count(*) as count')
                           ->get()
                           ->pluck('count', 'posisi');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_kandidat' => $total,
                'active_kandidat' => $active,
                'by_posisi' => $byPosisi
            ]
        ], 200);
    }

    /**
     * Get kandidat results ordered by votes.
     */
    public function results()
    {
        $kandidat = Kandidat::active()
                           ->orderByVotes()
                           ->with('votes')
                           ->get();

        return response()->json([
            'status' => 'success',
            'data' => $kandidat
        ], 200);
    }
}
