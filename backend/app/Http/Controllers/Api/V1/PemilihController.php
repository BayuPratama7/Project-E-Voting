<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PemilihController extends Controller
{
    /**
     * Display a listing of the pemilih.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $status = $request->get('status');
        $voted = $request->get('voted');

        $query = Pemilih::query();

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

        if ($voted !== null) {
            $query->where('has_voted', $voted === 'true');
        }

        $pemilih = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $pemilih
        ], 200);
    }

    /**
     * Store a newly created pemilih.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|unique:pemilih,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pemilih,email',
            'password' => 'required|string|min:6',
            'kelas' => 'required|string|max:50',
            'semester' => 'required|string|max:10',
            'status' => 'sometimes|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $pemilih = Pemilih::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'kelas' => $request->kelas,
            'semester' => $request->semester,
            'status' => $request->get('status', 'active'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pemilih created successfully',
            'data' => $pemilih
        ], 201);
    }

    /**
     * Display the specified pemilih.
     */
    public function show($id)
    {
        $pemilih = Pemilih::with('vote.kandidat')->find($id);

        if (!$pemilih) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemilih not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pemilih
        ], 200);
    }

    /**
     * Update the specified pemilih.
     */
    public function update(Request $request, $id)
    {
        $pemilih = Pemilih::find($id);

        if (!$pemilih) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemilih not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nim' => 'sometimes|string|unique:pemilih,nim,' . $id,
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:pemilih,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'kelas' => 'sometimes|string|max:50',
            'semester' => 'sometimes|string|max:10',
            'status' => 'sometimes|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only(['nim', 'nama', 'email', 'kelas', 'semester', 'status']);

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $pemilih->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Pemilih updated successfully',
            'data' => $pemilih->fresh()
        ], 200);
    }

    /**
     * Remove the specified pemilih.
     */
    public function destroy($id)
    {
        $pemilih = Pemilih::find($id);

        if (!$pemilih) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemilih not found'
            ], 404);
        }

        // Check if pemilih has voted
        if ($pemilih->hasVoted()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete pemilih who has already voted'
            ], 400);
        }

        $pemilih->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pemilih deleted successfully'
        ], 200);
    }

    /**
     * Search pemilih by various criteria.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $limit = $request->get('limit', 10);

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search query is required'
            ], 422);
        }

        $pemilih = Pemilih::where('nama', 'like', "%{$query}%")
                          ->orWhere('nim', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%")
                          ->limit($limit)
                          ->get();

        return response()->json([
            'status' => 'success',
            'data' => $pemilih
        ], 200);
    }

    /**
     * Get voting statistics for pemilih.
     */
    public function statistics()
    {
        $total = Pemilih::count();
        $active = Pemilih::active()->count();
        $voted = Pemilih::voted()->count();
        $notVoted = Pemilih::notVoted()->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_pemilih' => $total,
                'active_pemilih' => $active,
                'voted_pemilih' => $voted,
                'not_voted_pemilih' => $notVoted,
                'voting_percentage' => $total > 0 ? round(($voted / $total) * 100, 2) : 0
            ]
        ], 200);
    }
}
