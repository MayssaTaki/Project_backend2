<?php
namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(array $data): Category
    {
        return Category::create($data);
    }
    public function countCategories(): int
    {
        return Category::count();
    }

    public function getAll()
    {
        return Category::paginate(10);
    }

    public function findById($id): Category
    {
        return Category::findOrFail($id);
    }
    
    public function update($id, array $data): Category
    {
        $category = $this->findById($id);
        
        $category->update($data);
        return $category;
    }
    
    

    public function search(string $query)
    {
        return Category::where('name', 'LIKE', "%{$query}%")
            ->paginate(10);;
    }
}
