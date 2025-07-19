<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Kandidat;
use App\Pemilih;
use App\User;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get admin dashboard data.
     */
    public function admin()
    {
        // Overview statistics
        $totalPemilih = Pemilih::count();
        $activePemilih = Pemilih::active()->count();
        $totalKandidat = Kandidat::count();
        $activeKandidat = Kandidat::active()->count();
        $totalVotes = Vote::count();
        $votingPercentage = $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0;

        // Recent activities (last 10 votes)
        $recentVotes = Vote::with(['pemilih:id,nama,nim', 'kandidat:id,nama,posisi'])
                          ->orderBy('voted_at', 'desc')
                          ->limit(10)
                          ->get()
                          ->map(function ($vote) {
                              return [
                                  'id' => $vote->id,
                                  'pemilih_nama' => $vote->pemilih->nama,
                                  'pemilih_nim' => $vote->pemilih->nim,
                                  'kandidat_nama' => $vote->kandidat->nama,
                                  'kandidat_posisi' => $vote->kandidat->posisi,
                                  'voted_at' => $vote->voted_at
                              ];
                          });

        // Top candidates
        $topKandidates = Kandidat::active()
                                ->orderBy('vote_count', 'desc')
                                ->limit(5)
                                ->get(['id', 'nama', 'posisi', 'vote_count']);

        // Voting statistics by hour for today
        $hourlyStats = Vote::whereDate('voted_at', today())
                          ->selectRaw('HOUR(voted_at) as hour, COUNT(*) as count')
                          ->groupBy('hour')
                          ->orderBy('hour')
                          ->get()
                          ->pluck('count', 'hour');

        // Fill missing hours with 0
        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            $hours[$i] = $hourlyStats->get($i, 0);
        }

        // Voting by class
        $classStat = Vote::join('pemilih', 'votes.pemilih_id', '=', 'pemilih.id')
                        ->groupBy('pemilih.kelas')
                        ->selectRaw('pemilih.kelas, COUNT(*) as count')
                        ->orderBy('count', 'desc')
                        ->get()
                        ->pluck('count', 'kelas');

        return response()->json([
            'status' => 'success',
            'data' => [
                'overview' => [
                    'total_pemilih' => $totalPemilih,
                    'active_pemilih' => $activePemilih,
                    'total_kandidat' => $totalKandidat,
                    'active_kandidat' => $activeKandidat,
                    'total_votes' => $totalVotes,
                    'voting_percentage' => $votingPercentage,
                    'remaining_votes' => $totalPemilih - $totalVotes
                ],
                'recent_votes' => $recentVotes,
                'top_candidates' => $topKandidates,
                'hourly_voting_stats' => $hours,
                'voting_by_class' => $classStat,
                'last_updated' => now()
            ]
        ], 200);
    }

    /**
     * Get pemilih dashboard data.
     */
    public function pemilih($pemilihId)
    {
        $pemilih = Pemilih::with(['vote.kandidat'])->find($pemilihId);

        if (!$pemilih) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemilih not found'
            ], 404);
        }

        // Get all active candidates
        $candidates = Kandidat::active()
                             ->orderBy('nama')
                             ->get(['id', 'nama', 'nim', 'posisi', 'visi', 'misi', 'foto']);

        // Add foto_url to each candidate
        $candidates = $candidates->map(function ($candidate) {
            $candidate->foto_url = $candidate->foto_url;
            return $candidate;
        });

        // Voting statistics
        $totalVotes = Vote::count();
        $totalPemilih = Pemilih::active()->count();
        $votingPercentage = $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0;

        // Current voting results (without showing who voted for whom)
        $results = Kandidat::active()
                          ->orderBy('vote_count', 'desc')
                          ->get(['id', 'nama', 'posisi', 'vote_count'])
                          ->map(function ($kandidat) use ($totalVotes) {
                              return [
                                  'id' => $kandidat->id,
                                  'nama' => $kandidat->nama,
                                  'posisi' => $kandidat->posisi,
                                  'vote_count' => $kandidat->vote_count,
                                  'percentage' => $totalVotes > 0 ? round(($kandidat->vote_count / $totalVotes) * 100, 2) : 0
                              ];
                          });

        $data = [
            'pemilih_info' => [
                'id' => $pemilih->id,
                'nama' => $pemilih->nama,
                'nim' => $pemilih->nim,
                'kelas' => $pemilih->kelas,
                'semester' => $pemilih->semester,
                'has_voted' => $pemilih->has_voted,
                'voting_status' => $pemilih->has_voted ? 'completed' : 'pending'
            ],
            'candidates' => $candidates,
            'voting_stats' => [
                'total_votes' => $totalVotes,
                'total_pemilih' => $totalPemilih,
                'voting_percentage' => $votingPercentage
            ],
            'current_results' => $results
        ];

        // Add voted candidate info if pemilih has voted
        if ($pemilih->has_voted && $pemilih->vote) {
            $data['voted_candidate'] = [
                'id' => $pemilih->vote->kandidat->id,
                'nama' => $pemilih->vote->kandidat->nama,
                'posisi' => $pemilih->vote->kandidat->posisi,
                'voted_at' => $pemilih->vote->voted_at
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    /**
     * Get real-time statistics.
     */
    public function statistics()
    {
        $totalPemilih = Pemilih::active()->count();
        $totalVotes = Vote::count();
        $totalKandidat = Kandidat::active()->count();

        // Voting rate in the last hour
        $lastHourVotes = Vote::where('voted_at', '>=', now()->subHour())->count();

        // Most popular candidate
        $topCandidate = Kandidat::orderBy('vote_count', 'desc')->first();

        // Voting completion by class
        $classStat = DB::table('pemilih')
                      ->selectRaw('kelas, 
                                   COUNT(*) as total, 
                                   SUM(has_voted) as voted,
                                   ROUND((SUM(has_voted) / COUNT(*)) * 100, 2) as percentage')
                      ->where('status', 'active')
                      ->groupBy('kelas')
                      ->orderBy('percentage', 'desc')
                      ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'overview' => [
                    'total_pemilih' => $totalPemilih,
                    'total_votes' => $totalVotes,
                    'total_kandidat' => $totalKandidat,
                    'voting_percentage' => $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0,
                    'last_hour_votes' => $lastHourVotes
                ],
                'top_candidate' => $topCandidate ? [
                    'nama' => $topCandidate->nama,
                    'posisi' => $topCandidate->posisi,
                    'vote_count' => $topCandidate->vote_count
                ] : null,
                'class_statistics' => $classStat,
                'last_updated' => now()
            ]
        ], 200);
    }
}
