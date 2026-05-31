<?php

namespace App\Modules\Storefront\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Storefront\Http\Resources\StorefrontCategoryResource;
use App\Modules\Storefront\Http\Resources\StorefrontPlantResource;
use App\Modules\Storefront\Http\Resources\StorefrontProductResource;
use App\Modules\Storefront\Http\Resources\StorefrontSupplierResource;
use App\Modules\Storefront\Services\StorefrontService;

class StorefrontCatalogController
{
    public function __construct(
        private StorefrontService $storefrontService
    ) {}

    public function categories()
    {
        $categories = $this->storefrontService->categories();
        return ApiResponse::success(StorefrontCategoryResource::collection($categories));
    }

    public function products(Request $request)
    {
        $filters = [
            'category' => $request->query('category'),
            'search'   => $request->query('search'),
        ];
        $products = $this->storefrontService->products($filters);
        return ApiResponse::success(StorefrontProductResource::collection($products));
    }

    public function productById(string $id)
    {
        $product = $this->storefrontService->productById((int) $id);
        return ApiResponse::success(new StorefrontProductResource($product));
    }

    public function plants()
    {
        $plants = $this->storefrontService->plants();
        return ApiResponse::success(StorefrontPlantResource::collection($plants));
    }

    public function plantBySlug(string $slug)
    {
        $plant = $this->storefrontService->plantBySlug($slug);
        return ApiResponse::success(new StorefrontPlantResource($plant));
    }

    public function suppliers()
    {
        $suppliers = $this->storefrontService->suppliers();
        return ApiResponse::success(StorefrontSupplierResource::collection($suppliers));
    }
}
