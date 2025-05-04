<?php
namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Models\Category;
class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
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
    $category = $this->categoryRepository->findById($id);

    if (isset($data['image'])) {
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
        $data['image']->move(public_path('uploads/categories'), $imageName);
        $data['image'] = 'uploads/categories/' . $imageName;
    }

    $category->update($data);
    return $category;
}

    

    public function search(string $query)
{
    return $this->categoryRepository->search($query);
}
}
