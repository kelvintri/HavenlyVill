<?php

namespace App\Contracts;

/**
 * Interface ReportableInterface — kontrak untuk entitas yang bisa menghasilkan laporan.
 *
 * Menerapkan requirement (h): penggunaan interface.
 *
 * @package App\Contracts
 */
interface ReportableInterface
{
    /**
     * Menghasilkan data laporan untuk periode tertentu.
     *
     * @param string $period Periode laporan ('daily', 'weekly', 'monthly', 'yearly')
     * @return array Data laporan dalam bentuk array (Req f)
     */
    public function generateReport(string $period): array;

    /**
     * Mendapatkan data statistik ringkasan.
     *
     * @return array Array berisi statistik (Req f: penggunaan array)
     */
    public function getStatistics(): array;
}
