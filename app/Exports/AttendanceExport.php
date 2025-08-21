<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'ID Karyawan',
            'Nama Karyawan',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Keterangan'
        ];
    }

    public function map($attendance): array
    {
        return [
            \Carbon\Carbon::parse($attendance->date)->format('d/m/Y'),
            $attendance->employee->employee_id,
            $attendance->employee->name,
            $attendance->clock_in,
            $attendance->clock_out,
            ucfirst($attendance->status),
            $attendance->notes
        ];
    }
}