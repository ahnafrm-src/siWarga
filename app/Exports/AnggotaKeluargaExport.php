<?php

namespace App\Exports;

use App\Models\AnggotaKeluarga;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AnggotaKeluargaExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{

    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                ],
            ],
            
        ];
    }

    

    public function map($anggota): array
    {
        return [
            $anggota->kepala->nama_kepala,
            $anggota->nama,
            $anggota->jenis_kelamin == "l" ? "laki-laki" : "Perempuan",
            $anggota->tanggal_lahir ? Carbon::parse($anggota->tanggal_lahir)->format('d-m-Y') : null,
            $anggota->kelompokUsia
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kepala Keluarga',
            'Nama warga',
            'Jenis Kelamin',
            'Tanggal lahir',
            'Kelompok Usia'
        ];
    }

    public function __construct(?string $jenis_kelamin, ?string $kelompok_usia)
    {
        $this->jenis_kelamin = $jenis_kelamin;
        $this->kelompok_usia = Str::ucfirst($kelompok_usia);
        
    }

    public function collection()
    {
        // $anggota = AnggotaKeluarga::query()->where('jenis_kelamin', $this->jenis_kelamin);
        $anggota = AnggotaKeluarga::query()->when($this->jenis_kelamin, function ($query) {
            return $query->where('jenis_kelamin', $this->jenis_kelamin);
        })->with('kepala')->get();


        return $anggota->when($this->kelompok_usia, function ($query) {
            return $query->filter(function ($item) {
                return $item->kelompokUsia == $this->kelompok_usia;
            });
        });
    }
}
