<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pemilih extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'pemilih';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nim', 'nama', 'email', 'password', 'kelas', 'semester', 'status', 'has_voted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'has_voted' => 'boolean',
    ];

    /**
     * Get the vote associated with the pemilih.
     */
    public function vote()
    {
        return $this->hasOne(Vote::class);
    }

    /**
     * Get the voted kandidat through vote relationship.
     */
    public function votedKandidat()
    {
        return $this->hasOneThrough(Kandidat::class, Vote::class, 'pemilih_id', 'id', 'id', 'kandidat_id');
    }

    /**
     * Check if pemilih has already voted.
     */
    public function hasVoted()
    {
        return $this->has_voted || $this->vote()->exists();
    }

    /**
     * Mark pemilih as voted.
     */
    public function markAsVoted()
    {
        $this->update(['has_voted' => true]);
    }

    /**
     * Scope a query to only include active pemilih.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include pemilih who haven't voted.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotVoted($query)
    {
        return $query->where('has_voted', false);
    }

    /**
     * Scope a query to only include pemilih who have voted.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVoted($query)
    {
        return $query->where('has_voted', true);
    }
}
