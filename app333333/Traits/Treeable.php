<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait Treeable
{
    /**
     * Getting parent
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|Model[]|null
     */
    public function parent()
    {
        return self::find($this->parent_id);
    }

    /**
     * Getting children
     *
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Getting parent
     *
     * @return Model|null
     */
    public function getParent()
    {
        return $this->parent();
    }

    /**
     * Getting children
     *
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children()->get();
    }
}