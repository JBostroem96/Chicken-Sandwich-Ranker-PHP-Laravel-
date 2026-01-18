<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;

/**
 * User model representing application users
 */
class User extends Authenticatable {

    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast
     *
     * @return array
     */
    protected $casts = [
  
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];

    /**
     * The chicken sandwiches that belong to the user
     */
    public function chickenSandwiches(): BelongsToMany {

        return $this->belongsToMany(
            ChickenSandwich::class,
            'user_chicken_sandwiches',
            'user_id',
            'chicken_sandwich_id',
            
            
        )->withPivot('score', 'review')->withTimestamps();
    }

    //accessor for role-based permission ... works as a getter for the column name for better maintainability
    public function isAdmin(): bool {

        return (bool) $this->is_admin;
    }

    /**
     * Change the user's password
     * @param Request $request              the request object containing the input
     */
    public function changePassword(Request $request): RedirectResponse {

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
            
        ]);

        if ($validated['current_password'] === $validated['new_password']) {

            return back()->withErrors(['new_password' => 'New password must be different from the current password.']);

        } 

        if (!Hash::check($validated['current_password'], auth()->user()->password)) {

            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        return back()->with('success', 'Password updated successfully.');
    }
}
