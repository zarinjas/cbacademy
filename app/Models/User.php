<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a learner.
     */
    public function isLearner(): bool
    {
        return $this->role === 'learner';
    }

    /**
     * Get the user's role display name.
     */
    public function getRoleDisplayName(): string
    {
        return ucfirst($this->role);
    }

    /**
     * Get the viewer profile for this user.
     */
    public function viewerProfile(): HasOne
    {
        return $this->hasOne(ViewerProfile::class);
    }

    /**
     * Check if the user has completed their profile.
     */
    public function hasCompletedProfile(): bool
    {
        if ($this->isAdmin()) {
            return true; // Admins bypass profile requirement
        }

        return $this->viewerProfile && $this->viewerProfile->isComplete();
    }
}
