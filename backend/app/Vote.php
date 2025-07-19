<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pemilih_id', 'kandidat_id', 'ip_address', 'user_agent', 'voted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'voted_at' => 'datetime',
    ];

    /**
     * Get the pemilih that owns the vote.
     */
    public function pemilih()
    {
        return $this->belongsTo(Pemilih::class);
    }

    /**
     * Get the kandidat that owns the vote.
     */
    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a vote, set voted_at timestamp
        static::creating(function ($vote) {
            if (is_null($vote->voted_at)) {
                $vote->voted_at = now();
            }
        });

        // When a vote is created, increment kandidat vote count and mark pemilih as voted
        static::created(function ($vote) {
            $vote->kandidat->incrementVoteCount();
            $vote->pemilih->markAsVoted();
        });

        // When a vote is deleted, decrement kandidat vote count
        static::deleted(function ($vote) {
            $vote->kandidat->decrementVoteCount();
            $vote->pemilih->update(['has_voted' => false]);
        });
    }

    /**
     * Scope a query to include votes for today.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('voted_at', today());
    }

    /**
     * Scope a query to include votes for a specific date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $start
     * @param  string  $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('voted_at', [$start, $end]);
    }

    /**
     * Scope a query to group by kandidat.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroupByKandidat($query)
    {
        return $query->groupBy('kandidat_id');
    }
}
