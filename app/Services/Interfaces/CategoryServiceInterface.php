<?php

namespace App\Services\Interfaces;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

interface CategoryServiceInterface {
    public function createCategory(array $data): JsonResponse;
    public function countCategories(): int;
    public function getAllCategories();
public function updateCategory($id, array $data): Category;
    public function search(string $query);
}