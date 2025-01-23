<?php

namespace App\Support\Contracts\Model;

interface ExportsProductSelection
{
    /**
     * Add model query scoping with eager loaded relations and relations count
     * for exporting product selection.
     *
     * @return void
     */
    public function scopeWithRelationsForProductSelection($query);
}
