<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
       $clients = Client::with(['leadAction' => function ($query) {
        $query->with('user');
    }])->latest()->get();
    // return $clients;
        return view('admin.client', compact('clients'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:lead,qualified,proposal,negotiation,client,lost',
            'priority' => 'required|in:low,medium,high',
            'industry' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'source' => 'required|in:website,referral,cold_outreach,social_media,event,other',
            'next_follow_up' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $client = Client::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Client created successfully!',
            'client' => $client
        ]);
    }

    public function show(Client $client): JsonResponse
    {
        return response()->json($client);
    }

public function update(Request $request, Client $client): JsonResponse
{
    $validated = $request->validate([
        'company_name' => 'required|string|max:255',
        'contact_person' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('clients')->ignore($client->id)],
        'phone' => 'nullable|string|max:20',
        'status' => 'required|in:lead,qualified,proposal,negotiation,client,lost',
        'priority' => 'required|in:low,medium,high',
        'industry' => 'nullable|string|max:255',
        'budget' => 'nullable|numeric|min:0',
        'source' => 'required|in:website,referral,cold_outreach,social_media,event,other',
        'next_follow_up' => 'nullable|date',
        'notes' => 'nullable|string'
    ]);

    $client->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Client updated successfully!',
        'client' => $client
    ]);
}

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully!'
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('excel_file');
            $results = $this->processExcelFile($file);
            
            return response()->json([
                'success' => true,
                'message' => 'Clients imported successfully!',
                'imported_count' => $results['imported'],
                'skipped_count' => $results['skipped'],
                'skipped_rows' => $results['skipped_rows']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error importing file: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processExcelFile($file)
    {
        $path = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();
        
        $data = [];
        
        if ($extension === 'csv') {
            $data = $this->readCSV($path);
        } else {
            $data = $this->readExcelWithPhp($path, $extension);
        }
        
        return $this->importClients($data);
    }

    private function readCSV($path)
    {
        $data = [];
        $handle = fopen($path, 'r');
        
        if ($handle === FALSE) {
            throw new \Exception('Could not open CSV file');
        }
        
        $headers = fgetcsv($handle); // Get column headers
        
        if ($headers === FALSE) {
            fclose($handle);
            throw new \Exception('CSV file is empty or invalid');
        }
        
        // Clean headers
        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);
        
        $rowCount = 1;
        while (($row = fgetcsv($handle)) !== FALSE) {
            $rowCount++;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Ensure row has same number of columns as headers
            if (count($row) !== count($headers)) {
                // Pad or truncate row to match headers count
                if (count($row) < count($headers)) {
                    $row = array_pad($row, count($headers), '');
                } else {
                    $row = array_slice($row, 0, count($headers));
                }
            }
            
            $rowData = array_combine($headers, $row);
            $data[] = $rowData;
        }
        fclose($handle);
        
        return $data;
    }

    private function readExcelWithPhp($path, $extension)
    {
        // For Excel files, we'll use a simpler approach with file reading
        // This method works for both .xls and .xlsx by converting them to temporary CSV
        $tempCsvPath = tempnam(sys_get_temp_dir(), 'excel_import_') . '.csv';
        
        try {
            if ($extension === 'xlsx') {
                $this->convertXlsxToCsv($path, $tempCsvPath);
            } else {
                $this->convertXlsToCsv($path, $tempCsvPath);
            }
            
            $data = $this->readCSV($tempCsvPath);
            
            // Clean up temporary file
            if (file_exists($tempCsvPath)) {
                unlink($tempCsvPath);
            }
            
            return $data;
            
        } catch (\Exception $e) {
            // Clean up temporary file on error
            if (file_exists($tempCsvPath)) {
                unlink($tempCsvPath);
            }
            throw new \Exception('Failed to read Excel file: ' . $e->getMessage());
        }
    }

    private function convertXlsxToCsv($xlsxPath, $csvPath)
    {
        $zip = new \ZipArchive();
        $data = [];
        
        if ($zip->open($xlsxPath) !== TRUE) {
            throw new \Exception('Cannot open XLSX file');
        }
        
        // Find the first worksheet
        $sheetIndex = -1;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (strpos($filename, 'xl/worksheets/sheet') !== false) {
                $sheetIndex = $i;
                break;
            }
        }
        
        if ($sheetIndex === -1) {
            $zip->close();
            throw new \Exception('No worksheet found in XLSX file');
        }
        
        // Read shared strings
        $sharedStrings = [];
        if (($sharedStringsIndex = $zip->locateName('xl/sharedStrings.xml')) !== FALSE) {
            $sharedStringsXml = $zip->getFromIndex($sharedStringsIndex);
            $sharedStrings = $this->parseSharedStrings($sharedStringsXml);
        }
        
        // Read worksheet
        $worksheetXml = $zip->getFromIndex($sheetIndex);
        $zip->close();
        
        $data = $this->parseWorksheet($worksheetXml, $sharedStrings);
        $this->writeDataToCsv($data, $csvPath);
    }

    private function convertXlsToCsv($xlsPath, $csvPath)
    {
        // For .xls files, use a simpler approach - read as binary and parse
        // This is a basic implementation for simple .xls files
        $fileHandle = fopen($xlsPath, 'rb');
        if (!$fileHandle) {
            throw new \Exception('Cannot open XLS file');
        }
        
        $data = [];
        $isFirstRow = true;
        
        // Read file and look for readable text (simplified approach)
        $content = file_get_contents($xlsPath);
        
        // Extract rows using a simple pattern (this works for simple Excel files)
        preg_match_all('/\b([A-Za-z0-9\s@\.\-_]+)\b/s', $content, $matches);
        
        // Group into rows (this is a simplified approach)
        $allValues = $matches[1] ?? [];
        $row = [];
        $data = [];
        
        foreach ($allValues as $value) {
            // Skip very short values that are likely formatting
            if (strlen(trim($value)) > 1) {
                $row[] = trim($value);
                
                // Assume 5 columns per row for simple files
                if (count($row) >= 5) {
                    if ($isFirstRow) {
                        $isFirstRow = false;
                    }
                    $data[] = $row;
                    $row = [];
                }
            }
        }
        
        // If we have a partial row, add it
        if (!empty($row)) {
            $data[] = $row;
        }
        
        fclose($fileHandle);
        $this->writeDataToCsv($data, $csvPath);
    }

    private function parseSharedStrings($sharedStringsXml)
    {
        $sharedStrings = [];
        preg_match_all('/<t[^>]*>(.*?)<\/t>/s', $sharedStringsXml, $matches);
        return $matches[1] ?? [];
    }

    private function parseWorksheet($worksheetXml, $sharedStrings)
    {
        $data = [];
        preg_match_all('/<row[^>]*>(.*?)<\/row>/s', $worksheetXml, $rows);
        
        foreach ($rows[1] as $rowIndex => $rowContent) {
            preg_match_all('/<c[^>]*>.*?<v>(.*?)<\/v>.*?<\/c>/s', $rowContent, $cells);
            $rowData = [];
            
            foreach ($cells[1] as $cellValue) {
                // Check if it's a shared string reference
                if (is_numeric($cellValue) && isset($sharedStrings[$cellValue])) {
                    $rowData[] = $sharedStrings[$cellValue];
                } else {
                    $rowData[] = $cellValue;
                }
            }
            
            if (!empty($rowData)) {
                $data[] = $rowData;
            }
        }
        
        return $data;
    }

    private function writeDataToCsv($data, $csvPath)
    {
        $handle = fopen($csvPath, 'w');
        if ($handle === FALSE) {
            throw new \Exception('Cannot create temporary CSV file');
        }
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
    }

    private function importClients($data)
    {
        $imported = 0;
        $skipped = 0;
        $skippedRows = [];
        
        foreach ($data as $index => $row) {
            try {
                // Normalize column names (case-insensitive)
                $normalizedRow = [];
                foreach ($row as $key => $value) {
                    $normalizedKey = strtolower(trim($key));
                    $normalizedRow[$normalizedKey] = trim($value);
                }
                $row = $normalizedRow;
                
                // Validate required fields
                $requiredFields = ['company_name', 'contact_person', 'email'];
                $missingFields = [];
                
                foreach ($requiredFields as $field) {
                    if (empty($row[$field])) {
                        $missingFields[] = $field;
                    }
                }
                
                if (!empty($missingFields)) {
                    $skipped++;
                    $skippedRows[] = "Row " . ($index + 2) . ": Missing required fields - " . implode(', ', $missingFields);
                    continue;
                }
                
                // Validate email format
                if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                    $skipped++;
                    $skippedRows[] = "Row " . ($index + 2) . ": Invalid email format - " . $row['email'];
                    continue;
                }
                
                // Check for duplicate email
                if (Client::where('email', $row['email'])->exists()) {
                    $skipped++;
                    $skippedRows[] = "Row " . ($index + 2) . ": Duplicate email - " . $row['email'];
                    continue;
                }
                
                // Map and validate data
                $clientData = [
                    'company_name' => $row['company_name'],
                    'contact_person' => $row['contact_person'],
                    'email' => $row['email'],
                    'phone' => $row['phone'] ?? $row['phone_number'] ?? null,
                    'status' => $this->validateStatus($row['status'] ?? 'lead'),
                    'priority' => $this->validatePriority($row['priority'] ?? 'medium'),
                    'industry' => $row['industry'] ?? null,
                    'budget' => $this->parseBudget($row['budget'] ?? null),
                    'source' => $this->validateSource($row['source'] ?? 'other'),
                    'next_follow_up' => $this->parseDate($row['next_follow_up'] ?? $row['follow_up'] ?? null),
                    'notes' => $row['notes'] ?? $row['note'] ?? null,
                ];
                
                Client::create($clientData);
                $imported++;
                
            } catch (\Exception $e) {
                $skipped++;
                $skippedRows[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                Log::error("Import row error: " . $e->getMessage());
            }
        }
        
        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'skipped_rows' => $skippedRows
        ];
    }

    private function parseBudget($budget)
    {
        if (empty($budget)) {
            return null;
        }
        
        // Remove any currency symbols and thousands separators
        $budget = preg_replace('/[^\d.-]/', '', $budget);
        
        return is_numeric($budget) ? floatval($budget) : null;
    }

    private function validateStatus($status)
    {
        $validStatuses = ['lead', 'qualified', 'proposal', 'negotiation', 'client', 'lost'];
        $status = strtolower(trim($status));
        return in_array($status, $validStatuses) ? $status : 'lead';
    }

    private function validatePriority($priority)
    {
        $validPriorities = ['low', 'medium', 'high'];
        $priority = strtolower(trim($priority));
        return in_array($priority, $validPriorities) ? $priority : 'medium';
    }

    private function validateSource($source)
    {
        $validSources = ['website', 'referral', 'cold_outreach', 'social_media', 'event', 'other'];
        $source = strtolower(trim($source));
        return in_array($source, $validSources) ? $source : 'other';
    }

    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }
        
        try {
            // Handle Excel serial date numbers
            if (is_numeric($dateString)) {
                $excelBaseDate = \Carbon\Carbon::create(1899, 12, 30);
                return $excelBaseDate->addDays($dateString)->format('Y-m-d');
            }
            
            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}