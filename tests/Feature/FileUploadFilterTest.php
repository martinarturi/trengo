<?php

namespace Tests\Feature;

use Tests\TestCase;
use \Illuminate\Http\UploadedFile;

class FileUploadFilterTest extends TestCase
{
    /**
     * File uploading and filtering.
     */
    public function test_file_can_be_filtered(): void
    {
        $localFilePath = base_path('tests/Files/test-individual-data-upload.csv');

        $file = new UploadedFile($localFilePath, 'file.csv', null, null, true);

        $expectedResponse = [
            [
                "first_name" => "Ataturk",
                "last_name" => " Mustafa Kemal",
                "address" => " Akdeniz Cd. No:31 Cankaya",
                "city" => "Ankara",
                "postal_code" => "06570",
                "country" => "Turkiye",
                "date_of_birth" => "1881-10-29",
                "personal_description" => "Military officer, revolutionary, statesman, and founder of the Republic of Turkey."
            ]
        ];

        $response = $this->post('/api/individual/file-upload?city=Ankara', [
            'file' => $file,
        ],
        [
            "Content-Type" => "multipart/form-data",
            "Accept" => "application/json"
        ]);

        $response->assertStatus(200);
        $response->assertJson($expectedResponse);
    }
}
