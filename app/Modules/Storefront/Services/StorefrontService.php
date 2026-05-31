<?php

namespace App\Modules\Storefront\Services;

use App\Modules\Storefront\Repositories\StorefrontRepository;

class StorefrontService
{
    public function __construct(
        private StorefrontRepository $storefrontRepository
    ) {}

    public function categories()
    {
        return $this->storefrontRepository->categories();
    }

    public function products(array $filters)
    {
        return $this->storefrontRepository->products($filters);
    }

    public function productById(int $plantProductId)
    {
        return $this->storefrontRepository->productById($plantProductId);
    }

    public function plants()
    {
        return $this->storefrontRepository->plants();
    }

    public function plantBySlug(string $slug)
    {
        return $this->storefrontRepository->plantBySlug($slug);
    }

    public function suppliers()
    {
        return $this->storefrontRepository->suppliers();
    }
}
