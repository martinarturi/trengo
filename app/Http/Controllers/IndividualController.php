<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Http\Requests\IndividualFileUploadRequest;
use App\Services\IndividualDataManagerService;

class IndividualController extends Controller
{
    public function fileUploadForm(Request $request): InertiaResponse
    {
        return Inertia::render('IndividualFileUpload', []);
    }

    public function fileUpload(IndividualFileUploadRequest $request, IndividualDataManagerService $individualDataManagerService): JsonResponse
    {
        $age = $request->get('age', null);
        $city = $request->get('city', null);
        $country = $request->get('country', null);
        $name = $request->get('name', null);

        $filters = [
            'age' => $age,
            'city' => $city,
            'country' => $country,
            'name' => $name,
        ];

        $file = $request->file('file');

        $result = $individualDataManagerService->filterFromFile($file, $filters);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'] ?? 'An error occurred while processing the file and filters.',
                'error' => $result['error'] ?? 'Internal server error.'
            ], 500);
        }

        $headers = $individualDataManagerService->getFileImportHeaders($file);

        $requiredData = array_map(function ($item) use ($headers) {
            return array_intersect_key($item->toArray(), array_flip($headers));
        }, $result['data']);

        return response()->json($requiredData);
    }
}
