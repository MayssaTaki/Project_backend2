<?php
namespace App\Repositories\Contracts;
use App\Models\Category;

interface CategoryRepositoryInterface{
    public function create(array $data): Category;
    public function countCategories(): int;
    public function getAll();
    public function findById($id): Category;
 public function update($category, array $data): Category;
     public function search(string $query);
}