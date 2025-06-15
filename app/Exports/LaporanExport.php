<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class LaporanExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithEvents,
    WithCustomStartCell
{
    protected $laporans;
    protected $totalTerjual;
    protected $totalTransaksi;
    protected $totalKeuntungan;
    protected $title;

    public function __construct($laporans, $totalTerjual, $totalTransaksi, $totalKeuntungan, $title)
    {
        $this->laporans        = $laporans;
        $this->totalTerjual    = $totalTerjual;
        $this->totalTransaksi  = $totalTransaksi;
        $this->totalKeuntungan = $totalKeuntungan;
        $this->title           = $title;
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Modal',
            'Total',
            'Kasir',
            'Tanggal Transaksi',
        ];
    }

    public function collection()
    {
        $rows = new Collection();
        $no   = 1;

        foreach ($this->laporans as $laporan) {
            $rows->push([
                'No'                => $no++,
                // Pembungkus "=\"" untuk memastikan kode barang tampil lengkap sebagai teks
                'Kode Barang'       => '="' . $laporan->kode_barang . '"',
                'Nama Barang'       => $laporan->nama_barang,
                'Jumlah'            => $laporan->jumlah,
                'Modal'             => 'Rp ' . number_format($laporan->modal, 0, ',', '.'),
                'Total'             => 'Rp ' . number_format($laporan->total, 0, ',', '.'),
                'Kasir'             => $laporan->kasir,
                'Tanggal Transaksi' => Carbon::parse($laporan->transaction_date)
                                             ->translatedFormat('d F Y H:i'),
            ]);
        }

        $rows->push(['', '', '', '', '', '', '', '']);

        $rows->push([
            '', '', '', 'Total Terjual:', $this->totalTerjual, '', '', '',
        ]);

        $rows->push([
            '', '', '', 'Total Transaksi:', 'Rp ' . number_format($this->totalTransaksi, 0, ',', '.'), '', '', '',
        ]);

        $rows->push([
            '', '', '', 'Keuntungan:', 'Rp ' . number_format($this->totalKeuntungan, 0, ',', '.'), '', '', '',
        ]);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A2:H2')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E88E5'],
            ],
        ]);

        $sheet->getStyle('A2:H2')->getBorders()
              ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A2:H2')
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:H1');

                $niceTitle = str_replace(['_', '-'], ' ', $this->title);
                $sheet->setCellValue('A1', 'Data ' . ucfirst($niceTitle));

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'size'  => 14,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2196F3'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(25);

                $lastRow   = $sheet->getHighestRow();
                $rangeData = 'A2:H' . $lastRow;

                $sheet->getStyle($rangeData)
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Format kolom kode barang agar tidak menampilkan notasi ilmiah
                $sheet->getStyle("B3:B" . ($lastRow - 4))
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);

                // Styling warna baris total
                $sheet->getStyle("E" . ($lastRow - 2))->getFont()->getColor()->setRGB('000000'); // Hitam
                $sheet->getStyle("E" . ($lastRow - 1))->getFont()->getColor()->setRGB('388E3C'); // Hijau

                $isMinus = $this->totalKeuntungan < 0;
                $warnaKeuntungan = $isMinus ? 'F44336' : '1E40AF';
                $sheet->getStyle("E{$lastRow}")->getFont()->getColor()->setRGB($warnaKeuntungan);
            },
        ];
    }
}
