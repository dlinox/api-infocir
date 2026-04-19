<?php

namespace App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Requests\CertificateTemplate\CertificateTemplateRequest;
use App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Resources\CertificateTemplate\CertificateTemplateDataTableItemResource;
use App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Resources\CertificateTemplate\CertificateTemplateResource;
use App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Resources\CertificateTemplate\CertificateTemplateSelectItemResource;
use App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Services\CertificateTemplateService;

class CertificateTemplateController
{
    public function __construct(
        private CertificateTemplateService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = CertificateTemplateDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getSelectItems()
    {
        $items = $this->service->getSelectItems();
        return ApiResponse::success(CertificateTemplateSelectItemResource::collection($items));
    }

    public function getByEntity(string $entityType, int $entityId)
    {
        $template = $this->service->findByEntity($entityType, $entityId);

        return ApiResponse::success(
            $template ? new CertificateTemplateResource($template) : null
        );
    }

    public function getById(int $templateId)
    {
        $template = $this->service->findById($templateId);
        return ApiResponse::success(new CertificateTemplateResource($template));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'entity_type'   => 'required|string|in:course,program,training',
            'entity_id'     => 'required|integer',
            'name'          => 'nullable|string|max:150',
            'fields'        => 'nullable|array',
            'validity_days' => 'nullable|integer|min:1',
        ]);

        $entityType = $validated['entity_type'];
        $entityId = $validated['entity_id'];
        unset($validated['entity_type'], $validated['entity_id']);

        $template = $this->service->save($validated, $entityType, $entityId);
        return ApiResponse::success(
            new CertificateTemplateResource($template),
            'Plantilla guardada correctamente'
        );
    }

    public function updateTemplate(Request $request, int $templateId)
    {
        $validated = $request->validate([
            'name'          => 'nullable|string|max:150',
            'fields'        => 'nullable|array',
            'validity_days' => 'nullable|integer|min:1',
        ]);

        $template = $this->service->updateTemplate($templateId, $validated);
        return ApiResponse::success(
            new CertificateTemplateResource($template),
            'Plantilla guardada correctamente'
        );
    }

    public function uploadBackground(Request $request, string $entityType, int $entityId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        $result = $this->service->uploadBackground($entityType, $entityId, $request->file('file'));
        return ApiResponse::success($result, 'Imagen de fondo actualizada correctamente');
    }

    public function uploadBackgroundForTemplate(Request $request, int $templateId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        $result = $this->service->uploadBackgroundForTemplate($templateId, $request->file('file'));
        return ApiResponse::success($result, 'Imagen de fondo actualizada correctamente');
    }
}
