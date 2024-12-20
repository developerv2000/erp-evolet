<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Support\Helpers\FileHelper;
use App\Support\Traits\Model\UploadsFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use UploadsFile;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const DEFAULT_PREFERRED_THEME = 'light';
    const DEFAULT_COLLAPSED_LEFTBAR = false;
    const DEFAULT_LOCALE = 'ru';

    const PHOTO_PATH = 'img/users';
    const PHOTO_WIDTH = 400;
    const PHOTO_HEIGHT = 400;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
            'settings' => 'array',
        ];
    }

    protected $with = [
        'roles',
        'permissions',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getPhotoAssetUrlAttribute(): string
    {
        return asset(self::PHOTO_PATH . '/' . $this->photo);
    }

    public function getPhotoFilePathAttribute()
    {
        return public_path(self::PHOTO_PATH . '/' . $this->photo);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeOnlyBdms($query)
    {
        return $query->whereRelation('roles', 'name', Role::BDM_NAME);
    }

    public function scopeOnlyMADAnalysts($query)
    {
        return $query->whereRelation('roles', 'name', Role::MAD_ANALYST_NAME);
    }

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function isGlobalAdministrator()
    {
        return $this->hasRole(Role::GLOBAL_ADMINISTRATOR_NAME);
    }

    public function isInactive()
    {
        return $this->hasRole(Role::INACTIVE_NAME);
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    /**
     * Determine if the user has a given permission.
     *
     * This function checks if the user has the specified permission. User-specific permissions
     * (attached directly to the user) have a higher priority than role-based permissions.
     * If the user has been granted a permission directly, or via their role, they are considered
     * to have that permission unless explicitly denied. Denying permissions (e.g., 'CAN_NOT_*')
     * will override allowing permissions.
     *
     * @param string $permission The name of the permission to check.
     * @return bool True if the user has the permission, false otherwise.
     */
    public function hasPermission($permissionName)
    {
        // Check if there is an explicit "CAN_NOT_*" permission for the user first
        $deniedPermissionName = Permission::getDenyingPermission($permissionName);

        // If the user has the explicit "CAN_NOT_*" permission, deny access
        if ($this->permissions->contains('name', $deniedPermissionName)) {
            return false;
        }

        // Check user-specific permissions for explicit allow
        if ($this->permissions->contains('name', $permissionName)) {
            return true;
        }

        // Check for "CAN_NOT_*" permission in the user's roles
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $deniedPermissionName)) {
                return false; // If any role denies the permission, deny access
            }
        }

        // Check for explicit allow in the user's roles
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true; // Allow if the permission is found in any role
            }
        }

        return false; // Default deny if no permission is found
    }

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified setting for the user.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function updateSetting($key, $value): void
    {
        $settings = $this->settings;
        $settings[$key] = $value;

        $this->settings = $settings;
        $this->save();
    }

    /**
     * Reset users appearance & table column settings to default
     * Used after creating & updating users by admin
     *
     * Empty settings is used for Inactive users
     */
    public function resetAllSettingsToDefault(): void
    {
        // Refresh user because roles may have been updated
        $this->refresh();

        // Empty settings for Inactive users
        if ($this->isInactive()) {
            $this->update(['settings' => null]);
            return;
        }

        // Appearance settings
        $settings = [
            'preferred_theme' => User::DEFAULT_PREFERRED_THEME,
            'collapsed_dashboard_leftbar' => User::DEFAULT_COLLAPSED_LEFTBAR,
            'locale' => User::DEFAULT_LOCALE,
        ];

        $this->update(['settings' => $settings]);

        // Table settings
        $this->resetMADTablesColumnSettings($settings);
    }

    /**
     * Reset users MAD tables column settings
     */
    public function resetMADTablesColumnSettings($settings)
    {
        $this->refresh();
        $settings = $this->settings;

        $settings['manufacturers_table_columns'] = Manufacturer::getDefaultTableColumnsForUser($this);

        $this->update(['settings' => $settings]);
    }

    /**
     * Reset all settings to default for all users
     *
     * Used via artisan command line
     */
    public static function resetAllSettingsToDefaultForAll()
    {
        self::all()->each(function ($user) {
            $user->resetDefaultSettings();
        });
    }

    /**
     * Reset only specific table column settings
     *
     * Used via artisan command line
     */
    public function resetSpecificTableColumnSettings($table) {}

    /**
     * Collects all table columns for a given key from user settings.
     *
     * @param  string  $key
     * @return \Illuminate\Support\Collection
     */
    public function collectTableColumnsBySettingsKey($key): Collection
    {
        return collect($this->settings[$key])->sortBy('order');
    }

    /**
     * Filters out only the visible columns from the provided collection.
     *
     * @param  \Illuminate\Support\Collection  $columns
     * @return array
     */
    public static function filterOnlyVisibleColumns($columns): array
    {
        return $columns->filter(fn($column) => $column['visible'] ?? false)
            ->sortBy('order')
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    /**
     * Update the user's profile based on the request data.
     *
     * This method is used by users to update their own profile via the profile edit page.
     *
     * @param \Illuminate\Http\Request $request The request object containing the profile data.
     * @return void
     */
    public function updateProfile($request): void
    {
        $validatedData = $request->validated();

        // Update all fields except 'photo'
        $this->update(collect($validatedData)->except('photo')->toArray());

        // Upload user's photo if provided and resize it
        if ($request->hasFile('photo')) {
            $this->uploadFile('photo', public_path(self::PHOTO_PATH), $this->name);
            FileHelper::resizeImage($this->photo_file_path, self::PHOTO_WIDTH, self::PHOTO_HEIGHT);
        }
    }

    /**
     * Update the user's password from the profile edit page.
     *
     * This method is used by users to update their own password via the profile edit page.
     *
     * @param \Illuminate\Http\Request $request The request object containing the new password.
     * @return void
     */
    public function updateProfilePassword($request): void
    {
        // Update the user's password with the new hashed password
        $this->update([
            'password' => bcrypt($request->new_password),
        ]);

        // Logout other devices using the new password
        Auth::logoutOtherDevices($request->new_password);
    }
}
