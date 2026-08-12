<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format angka menjadi mata uang Rupiah tanpa desimal.
     * Contoh: 15000000 -> "Rp 15.000.000"
     */
    public static function rupiah(int|float|null $value): string
    {
        return 'Rp '.number_format((float) $value, 0, ',', '.');
    }

    /**
     * Ubah status (snake/kebab) menjadi label kapital yang mudah dibaca.
     * Contoh: closed_won -> "CLOSED WON"
     */
    public static function statusLabel(?string $status): string
    {
        if ($status === null || $status === '') {
            return '-';
        }

        return strtoupper(str_replace(['_', '-'], ' ', $status));
    }

    /**
     * Daftar status lead yang valid.
     */
    public static function leadStatuses(): array
    {
        return ['new', 'contacted', 'proposal', 'negotiation', 'won', 'lost'];
    }

    /**
     * Daftar stage deal yang valid.
     */
    public static function dealStages(): array
    {
        return ['qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
    }

    /**
     * Daftar status customer yang valid.
     */
    public static function customerStatuses(): array
    {
        return ['active', 'inactive', 'churned'];
    }
}
