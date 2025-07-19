<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Kandidat;
use App\Pemilih;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VotingController extends Controller
{
    /**
     * Cast a vote.
     */
    public function cast(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kandidat_id' => 'required|exists:kandidat,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $pemilih = $request->user();

        // Check if user is a pemilih
        if (!$pemilih instanceof Pemilih) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pemilih can vote'
            ], 403);
        }

        // Check if pemilih has already voted
        if ($pemilih->hasVoted()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already voted'
            ], 400);
        }

        // Check if pemilih is active
        if ($pemilih->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is not active'
            ], 403);
        }

        $kandidat = Kandidat::find($request->kandidat_id);

        // Check if kandidat is active
        if ($kandidat->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Kandidat is not active'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Create vote record
            $vote = Vote::create([
                'pemilih_id' => $pemilih->id,
                'kandidat_id' => $kandidat->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'voted_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Vote cast successfully',
                'data' => [
                    'vote_id' => $vote->id,
                    'kandidat' => [
                        'id' => $kandidat->id,
                        'nama' => $kandidat->nama,
                        'posisi' => $kandidat->posisi
                    ],
                    'voted_at' => $vote->voted_at
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to cast vote',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get voting results.
     */
    public function results()
    {
        $kandidat = Kandidat::active()
                           ->withCount('votes')
                           ->orderBy('votes_count', 'desc')
                           ->orderBy('vote_count', 'desc')
                           ->get();

        $totalVotes = Vote::count();
        $totalPemilih = Pemilih::active()->count();

        $results = $kandidat->map(function ($k) use ($totalVotes) {
            return [
                'id' => $k->id,
                'nama' => $k->nama,
                'nim' => $k->nim,
                'posisi' => $k->posisi,
                'foto_url' => $k->foto_url,
                'vote_count' => $k->vote_count,
                'percentage' => $totalVotes > 0 ? round(($k->vote_count / $totalVotes) * 100, 2) : 0
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'candidates' => $results,
                'statistics' => [
                    'total_votes' => $totalVotes,
                    'total_pemilih' => $totalPemilih,
                    'turnout_percentage' => $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0
                ]
            ]
        ], 200);
    }

    /**
     * Get voting status.
     */
    public function status()
    {
        $totalPemilih = Pemilih::active()->count();
        $totalVotes = Vote::count();
        $votingPercentage = $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0;

        // Get hourly voting statistics for today
        $hourlyStats = Vote::whereDate('voted_at', today())
                          ->selectRaw('HOUR(voted_at) as hour, COUNT(*) as count')
                          ->groupBy('hour')
                          ->orderBy('hour')
                          ->get()
                          ->pluck('count', 'hour');

        return response()->json([
            'status' => 'success',
            'data' => [
                'voting_active' => true, // This could be controlled by admin settings
                'total_pemilih' => $totalPemilih,
                'total_votes' => $totalVotes,
                'remaining_votes' => $totalPemilih - $totalVotes,
                'voting_percentage' => $votingPercentage,
                'hourly_stats' => $hourlyStats,
                'last_updated' => now()
            ]
        ], 200);
    }

    /**
     * Get voting history for a specific pemilih.
     */
    public function history($pemilihId)
    {
        $vote = Vote::with(['kandidat', 'pemilih'])
                   ->where('pemilih_id', $pemilihId)
                   ->first();

        if (!$vote) {
            return response()->json([
                'status' => 'error',
                'message' => 'No voting history found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'vote_id' => $vote->id,
                'kandidat' => [
                    'id' => $vote->kandidat->id,
                    'nama' => $vote->kandidat->nama,
                    'posisi' => $vote->kandidat->posisi,
                    'foto_url' => $vote->kandidat->foto_url
                ],
                'voted_at' => $vote->voted_at,
                'ip_address' => $vote->ip_address
            ]
        ], 200);
    }

    /**
     * Get detailed voting statistics.
     */
    public function statistics()
    {
        $totalVotes = Vote::count();
        $totalPemilih = Pemilih::active()->count();
        
        // Votes by position
        $votesByPosition = Vote::join('kandidat', 'votes.kandidat_id', '=', 'kandidat.id')
                              ->groupBy('kandidat.posisi')
                              ->selectRaw('kandidat.posisi, COUNT(*) as count')
                              ->get()
                              ->pluck('count', 'posisi');

        // Votes by class
        $votesByClass = Vote::join('pemilih', 'votes.pemilih_id', '=', 'pemilih.id')
                           ->groupBy('pemilih.kelas')
                           ->selectRaw('pemilih.kelas, COUNT(*) as count')
                           ->get()
                           ->pluck('count', 'kelas');

        // Daily voting trend for the last 7 days
        $dailyTrend = Vote::whereDate('voted_at', '>=', now()->subDays(7))
                         ->selectRaw('DATE(voted_at) as date, COUNT(*) as count')
                         ->groupBy('date')
                         ->orderBy('date')
                         ->get()
                         ->pluck('count', 'date');

        return response()->json([
            'status' => 'success',
            'data' => [
                'overview' => [
                    'total_votes' => $totalVotes,
                    'total_pemilih' => $totalPemilih,
                    'turnout_percentage' => $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0
                ],
                'votes_by_position' => $votesByPosition,
                'votes_by_class' => $votesByClass,
                'daily_trend' => $dailyTrend
            ]
        ], 200);
    }
}
