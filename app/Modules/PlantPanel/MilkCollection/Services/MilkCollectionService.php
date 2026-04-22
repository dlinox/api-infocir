<?php

namespace App\Modules\PlantPanel\MilkCollection\Services;

use App\Common\Exceptions\ApiException;
use App\Common\Services\FileService;
use App\Models\Dairy\MilkCollection;
use App\Models\Dairy\Supplier;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\MilkCollection\Repositories\MilkCollectionRepository;
use Illuminate\Http\Request;

class MilkCollectionService
{
    private const PHOTO_DISK   = 'dairy';
    private const PHOTO_FOLDER = 'milk-collections/photos';

    public function __construct(
        private MilkCollectionRepository $repository,
        private AuthService $authService,
        private FileService $fileService,
    ) {}

    public function dataTable(Request $request)
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->dataTable($request, $plantId);
    }

    public function findById(int $id): MilkCollection
    {
        $plantId = $this->authService->getMyPlantId();
        $collection = $this->repository->findByIdForPlant($id, $plantId);
        if (!$collection) throw new ApiException('Recolección no encontrada', 404);
        return $collection;
    }

    public function save(array $data): MilkCollection
    {
        $plantId = $this->authService->getMyPlantId();

        $qualityTest = $data['quality_test'] ?? null;
        $photoBase64 = $data['photo_base64'] ?? null;

        unset($data['quality_test']);
        unset($data['photo_base64']);

        if ($photoBase64) {
            $existingFileId = null;
            if (!empty($data['id'])) {
                $existing = MilkCollection::where('id', $data['id'])->where('plant_id', $plantId)->first();
                $existingFileId = $existing?->file_id;
            }

            if ($existingFileId) {
                $this->fileService->delete($existingFileId);
            }

            $coreFile = $this->fileService->uploadBase64($photoBase64, 'Foto de acopio');
            $coreFile = $this->fileService->moveToStorage($coreFile->id, self::PHOTO_DISK, self::PHOTO_FOLDER);
            $data['file_id'] = $coreFile->id;
        }

        $collection = $this->repository->createOrUpdate($data, $plantId);
        $this->repository->saveQualityTest($collection->id, $qualityTest);

        return $collection;
    }

    public function delete(int $id): void
    {
        $plantId = $this->authService->getMyPlantId();
        $this->repository->delete($id, $plantId);
    }

    public function updatePaymentStatus(int $id, string $status): void
    {
        $plantId = $this->authService->getMyPlantId();
        $collection = $this->repository->findByIdForPlant($id, $plantId);
        if (!$collection) throw new ApiException('Recolección no encontrada', 404);
        $collection->update(['payment_status' => $status]);
    }

    public function getSupplierSelectItems(): array
    {
        return Supplier::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'trade_name'])
            ->map(fn ($s) => [
                'value' => $s->id,
                'title' => $s->trade_name ?: $s->name,
            ])
            ->toArray();
    }
}
