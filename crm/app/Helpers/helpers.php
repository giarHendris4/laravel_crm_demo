<?php

use App\Helpers\FormatHelper;

if (! function_exists('format_rupiah')) {
    /**
     * Global helper: format angka menjadi Rupiah.
     */
    function format_rupiah(int|float|null $value): string
    {
        return FormatHelper::rupiah($value);
    }
}

if (! function_exists('status_label')) {
    /**
     * Global helper: ubah status menjadi label kapital.
     */
    function status_label(?string $status): string
    {
        return FormatHelper::statusLabel($status);
    }
}
