<?php

namespace App\Services;

use App\Services\DataManagerService;
use \Illuminate\Http\UploadedFile;
use App\Models\Individual;
use App\Imports\IndividualsImport;
use Maatwebsite\Excel\HeadingRowImport;

class IndividualDataManagerService
{
  private $dataManagerService;

  public function __construct(
    DataManagerService $dataManagerService
  ) {
    $this->dataManagerService = $dataManagerService;
  }

  public function validateData($data)
  {
    $validKeys = [
      "first_name",	"last_name", "address", "city", "postal_code", "country", "date_of_birth", "personal_description"
    ];

    $dataKeys = array_keys($data[0]);
    if (!$dataKeys || $dataKeys[0] == '0') {
      return [
        'success' => false,
        'error' => 'Please submit a file with headers in the first row and not starting with empty columns.'
      ];
    }
    
    $excessKeys = array_diff($dataKeys, $validKeys);
    if ($excessKeys) {
      return [
        'success' => false,
        'error' => 'Keys ' . implode(',', $excessKeys) . ' are not allowed.'
      ];
    }

    $missingKeys = array_diff($validKeys, array_keys($data[0]));
    if ($missingKeys) {
      return [
        'success' => false,
        'error' => 'Keys ' . implode(',', $missingKeys) . ' are missing.'
      ];
    }


    foreach($data as $key => $val) {
      $format = 'Y-m-d';
      $date = $val['date_of_birth'];

      $d = \DateTime::createFromFormat($format, $date);

      $validation = (!$d || !($d->format($format) == $date));

      if ($validation) {
        return [
          'success' => false,
          'error' => "Date not valid for row: " . json_encode($val)
        ];
      }
    }

    return [
      'success' => true,
    ];
  }

  public function readFromFile(UploadedFile $file)
  {
    $data = (new IndividualsImport)->toArray($file, null, \Maatwebsite\Excel\Excel::CSV)[0];

    $validation = $this->validateData($data);

    return [
      'success' => $validation['success'],
      'error' => $validation['error'] ?? null,
      'data' => $data
    ];
  }

  public function getFileImportHeaders(UploadedFile $file)
  {
      $headings = (new HeadingRowImport)->toArray($file);
      return $headings[0][0] ?? [];
  }

  public function filterFromFile(UploadedFile $file, array $filters): array
  {
    try {
      $readResult = $this->readFromFile($file);

      if (!$readResult['success']) {
        return [
          'success' => false,
          'message' => $readResult['error'],
          'error' => $readResult['error'],
        ];
      }

      $data = $readResult['data'];

    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => 'There was an error reading the file.',
        'error' => $e->getMessage()
      ];
    }

    $individuals = array_map(function($el) {
      return new Individual($el);
    }, $data);

    try {
      $filtered = $this->dataManagerService->filter($individuals, $filters);
    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => 'There was an error filtering the file.',
        'error' => $e->getMessage()
      ];
    }

    return [
      'success' => true,
      'data' => $filtered
    ];
  }
}