<?php

namespace App\Imports;

use App\Models\CompanyHoliday;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CompanyHolidaysImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \Illuminate\Support\Facades\Log::info('Importing Row:', $row);

        if (!isset($row['title']) || !isset($row['date'])) {
            \Illuminate\Support\Facades\Log::warning('Row missing title or date', $row);
            return null;
        }

        $date = $this->transformDate($row['date']);
        \Illuminate\Support\Facades\Log::info('Parsed Date:', ['original' => $row['date'], 'parsed' => $date]);

        return new CompanyHoliday([
            'company_id' => Auth::user()->company_id,
            'title' => $row['title'],
            'date' => $date,
            'category' => $row['category'] ?? 'Company Holiday',
            'description' => $row['description'] ?? null,
        ]);
    }

    private function transformDate($value)
    {
        try {
            // Check if it's numeric (Excel serial date)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            // Try standard format
            try {
                return Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                return Carbon::parse($value)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Date Parse Error: ' . $value . ' - ' . $e->getMessage());
            return date('Y-m-d'); // Fallback to today or handle error
        }
    }
}
