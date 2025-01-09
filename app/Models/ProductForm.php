<?php

namespace App\Models;

use App\Support\Traits\Model\GetsMinifiedRecordsWithName;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class ProductForm extends Model
{
    use ScopesOrderingByName;
    use GetsMinifiedRecordsWithName;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'form_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getParentNameAttribute()
    {
        return $this->parent ? $this->parent->name : $this->name;
    }

    /*
    |--------------------------------------------------------------------------
    | Queries
    |--------------------------------------------------------------------------
    */

    public function scopeOnlyParents()
    {
        return self::whereNull('parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Get the IDs of all related records in the family tree, including the current record.
     *
     * If the current record has a parent, it includes the IDs of all its children and itself.
     * If the current record has no parent, it includes only its own ID.
     *
     * Used while checking similar products on products.create page.
     *
     * @return \Illuminate\Support\Collection|array The IDs of all related records in the family tree.
     */
    public function getFamilyIDs()
    {
        // If the current record has a parent, use the parent; otherwise, use the current record itself
        $parent = $this->parent ?: $this;

        // Pluck child IDs and push parent ID
        $IDs = $parent->childs->pluck('id')->toArray();
        $IDs[] = $parent->id;

        return $IDs;
    }
}
