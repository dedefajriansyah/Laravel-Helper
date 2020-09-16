<?php
/*
 * Author Dede Fajriansyah
 * Created on Wednesday April 8th 2020
 * Email dede.fajriansyah97@gmail.com
 * Telegram https://t.me/dedefajriansyah
 *
 * Copyright (c) 2020 FAJRIANSYAH.COM
 */

namespace Fajriansyah\Traits;

trait ModelDefaultSelect
{
    /**
     * Scope a query to only include default select
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefaultSelect($query)
    {
        return $query->select(array_keys($this->casts));
    }
}
