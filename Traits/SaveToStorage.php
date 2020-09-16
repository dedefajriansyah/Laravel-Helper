<?php
/*
 * Author Dede Fajriansyah
 * Created on Monday March 30th 2020
 * Email dede.fajriansyah97@gmail.com
 * Telegram https://t.me/dedefajriansyah
 *
 * Copyright (c) 2020 FAJRIANSYAH.COM
 */

namespace Fajriansyah\Traits;

use Illuminate\Support\Facades\Storage;

trait SaveToStorage
{
    /**
     * Save to storage.
     *
     * @param [type] $file
     * @param string $path
     * @return void
     */
    public function saveToStorage(string $disk = "public", $file, string $path)
    {
        return Storage::disk($disk)->putFile($path, $file);
    }
}
