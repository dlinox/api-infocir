<?php

namespace App\Modules\Admin\Dairy\Supplier\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;
use App\Models\Core\Profile;
use App\Models\Dairy\Supplier;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Shared\Repositories\ProfileRepository;
use Illuminate\Support\Facades\DB;

class CreateOrUpdateSupplierAction
{
    public function __construct(
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
        private ProfileRepository $profileRepository,
    ) {}

    public function execute(array $data): Supplier
    {
        try {
            DB::beginTransaction();

            $personData = $data['person'];
            $isUpdate = !empty($personData['id']);

            $result = $isUpdate ? $this->update($data) : $this->create($data);

            DB::commit();

            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function create(array $data): Supplier
    {
        $personData = $data['person'];

        $person = $this->createOrUpdatePersonAction->execute($personData);

        $existingSupplier = Supplier::where('person_id', $person->id)->first();
        if ($existingSupplier) {
            throw new ApiException('La persona ya es un proveedor');
        }

        $userId = $this->findExistingUserId($person->id);

        if (!$userId) {
            $user = $this->createOrUpdateUserAction->execute([
                'username' => $person->document_number,
                'password' => $person->document_number,
                'email' => $person->email,
                'is_active' => true,
            ]);
            $userId = $user->id;
        }

        $supplier = Supplier::create([
            'person_id' => $person->id,
            'supplier_type' => $data['supplier_type'] ?? 'individual',
            'trade_name' => $data['trade_name'] ?? null,
            'cellphone' => $data['cellphone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        $coreProfile = Profile::create([
            'person_id' => $person->id,
            'profileable_type' => 'dairy_suppliers',
            'profileable_id' => $person->id,
        ]);

        $supplierRole = Role::where('name', 'supplier_manager')->first();
        $this->profileRepository->create($userId, $supplierRole->id, $coreProfile->id);

        return $supplier;
    }

    private function update(array $data): Supplier
    {
        $personData = $data['person'];

        $person = $this->createOrUpdatePersonAction->execute($personData);

        $supplier = Supplier::where('person_id', $person->id)->firstOrFail();

        $supplier->update([
            'supplier_type' => $data['supplier_type'] ?? 'individual',
            'trade_name' => $data['trade_name'] ?? null,
            'cellphone' => $data['cellphone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        $userId = $this->findExistingUserId($person->id);

        if ($userId) {
            $this->createOrUpdateUserAction->execute([
                'id' => $userId,
                'username' => $person->document_number,
                'email' => $person->email,
            ]);
        }

        return $supplier;
    }

    private function findExistingUserId(int $personId): ?int
    {
        $result = DB::table('behavior_profiles')
            ->join('core_profiles', 'core_profiles.id', '=', 'behavior_profiles.core_profile_id')
            ->where('core_profiles.person_id', $personId)
            ->select('behavior_profiles.user_id')
            ->first();

        return $result?->user_id;
    }
}
