<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Patient::all();
    }

    public function headings(): array
    {
        return [
            'N° HISTORIA',
            'APELLIDOS',
            'NOMBRES',
            'CÉDULA',
            'DIRECCIÓN',
            'GRUPO DISPENSARIO',
            'TIPO VIVIENDA',
            'FACTOR RIESGO',
            'FECHA NACIMIENTO',
            'EDAD',
            'SEXO',
            'TELÉFONO',
            'FECHA CONSULTA',
            'PRÓXIMA CONSULTA',
            'DIAGNÓSTICO',
            'ESCOLARIDAD',
            'OCUPACIÓN',
            'PROFESIÓN',
            'DISCAPACIDAD',
            'CLASIFICACIÓN',
            'OBSERVACIÓN'
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->medical_history_number,
            $patient->last_name,
            $patient->first_name,
            $patient->id_number,
            $patient->address,
            $patient->dispensary_group,
            $patient->housing_type,
            $patient->risk_factor,
            $patient->birth_date->format('d/m/Y'),
            $patient->age,
            $patient->gender == 'M' ? 'Masculino' : 'Femenino',
            $patient->phone ?? 'N/A',
            $patient->consultation_date->format('d/m/Y'),
            $patient->next_consultation ? $patient->next_consultation->format('d/m/Y') : 'N/A',
            $patient->diagnosis,
            $patient->education_level,
            $patient->occupation,
            $patient->profession ?? 'N/A',
            $patient->disability,
            $patient->classification ?? 'N/A',
            $patient->observation ?? 'N/A'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para el encabezado
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2C3E50']]
            ],
        ];
    }
}