<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Support\Helpers\FileHelper;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\AddsDefaultQueryParamsToRequest;
use App\Support\Traits\Model\FinalizesQueryForRequest;
use App\Support\Traits\Model\UploadsFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use UploadsFile;
    use AddsDefaultQueryParamsToRequest;
    use FinalizesQueryForRequest;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const DEFAULT_ORDER_BY = 'name';
    const DEFAULT_ORDER_TYPE = 'asc';
    const DEFAULT_PAGINATION_LIMIT = 50;

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
        'department_id',
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

    public function responsibleCountries()
    {
        return $this->belongsToMany(Country::class, 'responsible_country_user');
    }

    public function analystManufacturers()
    {
        return $this->hasMany(Manufacturer::class, 'analyst_user_id');
    }

    public function productSearches()
    {
        return $this->hasMany(ProductSearch::class);
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

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
            'department',
            'roles',
            'permissions',
            'responsibleCountries',
        ]);
    }

    /**
     * Load basic relations, while sending notifications.
     *
     * Roles with permissions must be loaded, because of using gates.
     */
    public function scopeWithBasicRelationsToNotify($query)
    {
        return $query->with([
            'roles' => function ($rolesQuery) {
                $rolesQuery->with('permissions');
            },
            'permissions',
            'responsibleCountries',
        ]);
    }

    public function scopeOnlyBDMs($query)
    {
        return $query->whereRelation('roles', 'name', Role::BDM_NAME);
    }

    public function scopeOnlyMADAnalysts($query)
    {
        return $query->whereRelation('roles', 'name', Role::MAD_ANALYST_NAME);
    }

    /*
    |--------------------------------------------------------------------------
    | Relation loads
    |--------------------------------------------------------------------------
    */

    /**
     * Load basic relations for authenticated user.
     *
     * Used in EnsureUserRelationsAreLoaded middleware.
     */
    public function loadBasicAuthRelations()
    {
        $this->loadMissing([
            'roles' => function ($rolesQuery) {
                $rolesQuery->with('permissions');
            },
            'permissions',
            'responsibleCountries',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Queries
    |--------------------------------------------------------------------------
    */

    public static function getBDMsMinifed()
    {
        return self::onlyBdms()->select('id', 'name')->get();
    }

    public static function getMADAnalystsMinified()
    {
        return self::onlyMADAnalysts()->select('id', 'name')->get();
    }

    public static function getAllMinified()
    {
        return self::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
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

    public function isInactive()
    {
        return $this->hasRole(Role::INACTIVE_NAME);
    }

    public function isGlobalAdministrator()
    {
        return $this->hasRole(Role::GLOBAL_ADMINISTRATOR_NAME);
    }

    public function isMADAdministrator()
    {
        return $this->hasRole(Role::MAD_ADMINISTRATOR_NAME);
    }

    public function isAnyAdministrator()
    {
        return $this->isGlobalAdministrator()
            || $this->isMADAdministrator();
    }

    public function isMADAnalyst()
    {
        return $this->hasRole(Role::MAD_ANALYST_NAME);
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
            $this->settings = null;
            $this->save();
            return;
        }

        // Appearance settings
        $settings = [
            'preferred_theme' => User::DEFAULT_PREFERRED_THEME,
            'collapsed_leftbar' => User::DEFAULT_COLLAPSED_LEFTBAR,
            'locale' => User::DEFAULT_LOCALE,
        ];

        $this->settings = $settings;
        $this->save();

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
        $settings[Manufacturer::SETTINGS_MAD_TABLE_COLUMNS_KEY] = Manufacturer::getDefaultMADTableColumnsForUser($this);
        $settings[Product::SETTINGS_MAD_TABLE_COLUMNS_KEY] = Product::getDefaultMADTableColumnsForUser($this);
        $settings[Process::SETTINGS_MAD_TABLE_COLUMNS_KEY] = Process::getDefaultMADTableColumnsForUser($this);
        $settings[ProductSearch::SETTINGS_MAD_TABLE_COLUMNS_KEY] = ProductSearch::getDefaultMADTableColumnsForUser($this);
        $settings[Meeting::SETTINGS_MAD_TABLE_COLUMNS_KEY] = Meeting::getDefaultMADTableColumnsForUser($this);

        $this->settings = $settings;
        $this->save();
    }

    /**
     * Reset all settings to default for all users
     *
     * Used via artisan command line
     */
    public static function resetAllSettingsToDefaultForAll()
    {
        self::all()->each(function ($user) {
            $user->resetAllSettingsToDefault();
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
            $this->uploadPhoto();
        }
    }

    /**
     * Update the user's password from the profile edit page.
     *
     * Important: Laravel automatically logouts user from other devices, while user is updating his own password.
     *
     * @param \Illuminate\Http\Request $request The request object containing the new password.
     * @return void
     */
    public function updateProfilePassword($request): void
    {
        $this->update([
            'password' => bcrypt($request->new_password),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Filtering
    |--------------------------------------------------------------------------
    */

    public static function filterQueryForRequest($query, $request)
    {
        // Apply base filters using helper
        $query = QueryFilterHelper::applyFilters($query, $request, self::getFilterConfig());

        return $query;
    }

    private static function getFilterConfig(): array
    {
        return [
            'whereIn' => ['id', 'department_id'],
            'like' => ['email'],
            'dateRange' => ['created_at', 'updated_at'],
            'belongsToMany' => ['permissions', 'roles', 'responsibleCountries'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    /**
     * Create new user by admin.
     */
    public static function createFromRequest($request)
    {
        $record = self::create($request->validated());

        // Attach belongsToMany associations
        $record->roles()->attach($request->input('roles'));
        $record->permissions()->attach($request->input('permissions'));
        $record->responsibleCountries()->attach($request->input('responsibleCountries'));

        // Load all settings for the user
        $record->resetAllSettingsToDefault();

        // Upload user's photo
        $record->uploadPhoto();
    }

    /**
     * Update user by admin.
     *
     * Logouts user from all devices.
     */
    public function updateFromRequest($request)
    {
        // Update the user's profile
        $this->update($request->validated());

        // BelongsToMany relations
        $this->roles()->sync($request->input('roles'));
        $this->permissions()->sync($request->input('permissions'));
        $this->responsibleCountries()->sync($request->input('responsibleCountries'));

        // Reset settings
        $this->resetAllSettingsToDefault();

        // Manually logout user from all devices
        if (Auth::user()->id != $this->id) {
            $this->logoutFromAllSessions();
        }

        // Upload user's photo if provided
        if ($request->hasFile('photo')) {
            $this->uploadPhoto();
        }
    }

    /**
     * Update users password by admin.
     *
     * Laravel automatically logouts user from other devices, while user is updating his own password.
     * Thats why manually logout user from all devices, if not own password is being updated.
     */
    public function updatePassword($request): void
    {
        // Update the user's password with the new hashed password
        $this->update([
            'password' => bcrypt($request->password),
        ]);

        // Manually logout from all devices
        if (Auth::user()->id != $this->id) {
            $this->logoutFromAllSessions();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Logout user manually from all devices, by clearing sessions and remember_token.
     *
     * Laravel automatically logouts user from other devices, while user is updating his own password.
     * Used only while updating users by admin!
     */
    private function logoutFromAllSessions(): void
    {
        // Delete all sessions for the current user
        DB::table('sessions')->where('user_id', $this->id)->delete();

        // Delete users remember_token.
        $this->refresh();
        $this->remember_token = null;
        $this->save();
    }

    /**
     * Upload users photo.
     */
    public function uploadPhoto()
    {
        $this->uploadFile('photo', public_path(self::PHOTO_PATH), $this->name);
        FileHelper::resizeImage($this->photo_file_path, self::PHOTO_WIDTH, self::PHOTO_HEIGHT);
    }

    /**
     * Detect users home page, based on permissions.
     */
    public function detectHomeRouteName()
    {
        $homepageRoutes = [
            // MAD department
            'manufacturers.index' => 'view-MAD-EPP',
            'product-searches.index' => 'view-MAD-KVPP',
            'products.index' => 'view-MAD-IVP',
            'processes.index' => 'view-MAD-VPS',
        ];

        foreach ($homepageRoutes as $routeName => $gate) {
            if (Gate::allows($gate)) {
                return route($routeName);
            }
        }

        // Default home if no pages are accessible
        return route('profile.edit');
    }

    /**
     * Notify required users on process update to contract stage.
     */
    public static function notifyProcessOnContractStageToAll($notification)
    {
        self::withBasicRelationsToNotify()->each(function ($user) use ($notification) {
            if (Gate::forUser($user)->allows('receive-notification-on-MAD-VPS-contract')) {
                $user->notify($notification);
            }
        });
    }
}
