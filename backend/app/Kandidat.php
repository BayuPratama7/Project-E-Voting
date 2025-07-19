<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    protected $table = 'kandidat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'nim', 'email', 'kelas', 'semester', 'visi', 'misi', 'foto', 'posisi', 'status', 'vote_count'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vote_count' => 'integer',
    ];

    /**
     * Get the votes for the kandidat.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the pemilih who voted for this kandidat.
     */
    public function voters()
    {
        return $this->hasManyThrough(Pemilih::class, Vote::class, 'kandidat_id', 'id', 'id', 'pemilih_id');
    }

    /**
     * Increment vote count for this kandidat.
     */
    public function incrementVoteCount()
    {
        $this->increment('vote_count');
    }

    /**
     * Decrement vote count for this kandidat.
     */
    public function decrementVoteCount()
    {
        $this->decrement('vote_count');
    }

    /**
     * Get the foto URL attribute.
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/kandidat/' . $this->foto);
        }
        return asset('storage/kandidat/default.png');
    }

    /**
     * Scope a query to only include active kandidat.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to order by vote count.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByVotes($query)
    {
        return $query->orderBy('vote_count', 'desc');
    }

    /**
     * Scope a query to filter by position.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $posisi
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPosisi($query, $posisi)
    {
        return $query->where('posisi', $posisi);
    }
}
