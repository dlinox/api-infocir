<?php

namespace App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Services;

use Illuminate\Http\UploadedFile;
use App\Common\Helpers\FileHelper;
use App\Common\Services\FileService;
use App\Models\Learning\Course;
use App\Models\Learning\Program;
use App\Models\Learning\Training;
use App\Models\Learning\CertificateTemplate;
use App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Repositories\CertificateTemplateRepository;
use Illuminate\Support\Facades\Auth;

class CertificateTemplateService
{
    private const ENTITY_MODELS = [
        'course'   => Course::class,
        'program'  => Program::class,
        'training' => Training::class,
    ];

    private const BG_DISK   = 'learning';
    private const BG_FOLDER = 'certificates/backgrounds';

    public function __construct(
        private CertificateTemplateRepository $repository,
        private FileService $fileService,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request);
    }

    public function getSelectItems()
    {
        return $this->repository->getSelectItems();
    }

    public function findByEntity(string $entityType, int $entityId)
    {
        return $this->repository->findByEntity($entityType, $entityId);
    }

    public function findById(int $templateId)
    {
        return $this->repository->findById($templateId);
    }

    public function save(array $data, string $entityType, int $entityId)
    {
        return $this->repository->createOrUpdate($data, $entityType, $entityId);
    }

    public function updateTemplate(int $templateId, array $data)
    {
        return $this->repository->updateTemplate($templateId, $data);
    }

    public function uploadBackground(string $entityType, int $entityId, UploadedFile $file): array
    {
        $modelClass = self::ENTITY_MODELS[$entityType];
        $entity = $modelClass::with('certificateTemplate')->findOrFail($entityId);

        $coreFile = $this->replaceBackground($entity->certificateTemplate?->background_file_id, $file);

        if ($entity->certificateTemplate) {
            $entity->certificateTemplate->update(['background_file_id' => $coreFile->id]);
        } else {
            $template = CertificateTemplate::create([
                'name'               => 'Plantilla — ' . $entity->name,
                'background_file_id' => $coreFile->id,
                'created_by'         => Auth::user()->id,
            ]);
            $entity->update(['certificate_template_id' => $template->id]);
        }

        return $this->buildBackgroundResponse($coreFile);
    }

    public function uploadBackgroundForTemplate(int $templateId, UploadedFile $file): array
    {
        $template = CertificateTemplate::findOrFail($templateId);

        $coreFile = $this->replaceBackground($template->background_file_id, $file);
        $template->update(['background_file_id' => $coreFile->id]);

        return $this->buildBackgroundResponse($coreFile);
    }

    private function replaceBackground(?int $oldFileId, UploadedFile $file)
    {
        if ($oldFileId) {
            $this->fileService->delete($oldFileId);
        }

        $coreFile = $this->fileService->upload($file);

        return $this->fileService->moveToStorage($coreFile->id, self::BG_DISK, self::BG_FOLDER);
    }

    private function buildBackgroundResponse($coreFile): array
    {
        return [
            'url'    => FileHelper::getFileUrl(self::BG_DISK, $coreFile->filepath),
            'fileId' => $coreFile->id,
        ];
    }
}
