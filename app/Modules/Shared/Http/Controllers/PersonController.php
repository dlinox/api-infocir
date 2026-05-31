<?php

namespace App\Modules\Shared\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Shared\Http\Requests\Person\SearchByDocumentRequest;
use App\Modules\Shared\Http\Resources\Person\PersonResource;
use App\Modules\Shared\Http\Resources\Person\PersonSearchResource;
use App\Modules\Shared\Http\Resources\PersonItemResource;
use App\Modules\Shared\Services\PersonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonController
{
    public function __construct(
        private PersonService $personService
    ) {}

    public function selectAsyncItems(Request $request)
    {
        $items = $this->personService->selectAsyncItems($request);
        $items = PersonItemResource::collection($items);
        return ApiResponse::success($items);
    }

    public function searchByDocument(SearchByDocumentRequest $request): JsonResponse
    {
        $result = $this->personService->searchByDocument(
            $request->document_type,
            $request->document_number,
            $request->profile,
            $request->id ? (int) $request->id : null,
        );

        return ApiResponse::success(
            $result ? new PersonSearchResource($result) : null
        );
    }

    public function get(int $id): JsonResponse
    {
        $person = $this->personService->getById($id);
        return ApiResponse::success($person ? new PersonResource($person) : null);
    }
}
