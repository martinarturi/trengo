<?php

namespace App\Services;

class DataManagerService
{
  public function filter(array $data, array $filters): array
  {
    $filteredData = $data;

    foreach($filters as $key => $value)
    {
      if ($value == null) {
        continue;
      }

      $filteredData = array_filter($filteredData, function ($element) use ($key, $value) {
        return isset($element[$key]) && $element[$key] == $value;
      }, 1);
    }

    return array_values($filteredData);
  }
}