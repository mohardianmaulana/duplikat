<?php

namespace App\Charts;

use App\Models\Barang;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class JumlahBarangChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $jumlahBarang = Barang::all();

$barang = [];
$label = [];

foreach ($jumlahBarang as $barangItem) {
    $barang[] = $barangItem->jumlah;
    $label[] = $barangItem->nama; // Ganti 'nama' dengan kolom yang sesuai dari tabel 'Toko'
}

return $this->chart->donutChart()
    ->setTitle('Stok Barang')
    ->setSubtitle(date('M'))
    ->setWidth(300)
    ->setHeight(300)
    ->addData($barang)
    ->setLabels($label);
    }
}
