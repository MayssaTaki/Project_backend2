<?php
namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;


class CategoryService implements CategoryServiceInterface
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(array $data): JsonResponse
{
    try {
        if (isset($data['image'])) {
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $data['image']->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/' . $imageName;
        }

        $category = $this->categoryRepository->create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء الكاتيغوري بنجاح.',
            'data' => $category
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'فشل في إنشاء الكاتيغوري: ' . $e->getMessage()
        ], 500);
    }
}

    public function countCategories(): int
    {
        
        return $this->categoryRepository->countCategories();
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAll();
    }

public function updateCategory($id, array $data): Category
{
    $category = Category::findOrFail($id);

    $category->name = $data['name'];

    if (isset($data['image'])) {
        $path = $data['image']->store('categories', 'public');
        $category->image = $path;
    }
    $category->save();

    $category = $category->fresh();

    if ($category->image) {
        $category->image = asset('storage/' . $category->image);
    }

    return $category;
}


    public function search(string $query)
{
    return $this->categoryRepository->search($query);
}


}
