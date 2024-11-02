<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\DataManagerService;

class DataManagerServiceTest extends TestCase
{
    /**
     * Data filter testing.
     */
    public function test_data_filter(): void
    {
        $testData = [
            [
                'name' => 'Rick',
                'city' => 'Amsterdam'
            ],
            [
                'name' => 'John',
                'city' => 'Utrecht'
            ]
        ];

        $expectedFilteredResponse = [[
            'name' => 'John',
            'city' => 'Utrecht'
        ]];

        $testFilters = [
            'city' => 'Utrecht'
        ];

        $dataManagerServices = new DataManagerService();

        $filteredData = $dataManagerServices->filter($testData, $testFilters);

        $this->assertEquals($filteredData, $expectedFilteredResponse);
    }
}
