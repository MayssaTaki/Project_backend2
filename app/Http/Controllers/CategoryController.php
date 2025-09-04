<?php

namespace App\Http\Controllers;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'اسم الفئة مكرر. الرجاء اختيار اسم مختلف.',
            ], 400); 
        }

        $category = $this->categoryService->createCategory($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء الفئة بنجاح.',
            'data' => $category,
        ], 201); 
    }

    public function countCategories(): JsonResponse
    {
        try {
            $categoryCount = $this->categoryService->countCategories();
            return response()->json([
                'status' => 'success',
                'message' => 'تم جلب عدد الفئات  بنجاح.',
                'data' => [
                    'category_count' => $categoryCount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function getAll(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();

        if ($categories->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'لا توجد فئات حتى الآن.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم جلب جميع الفئات بنجاح.',
            'data' => CategoryResource::collection($categories),
        ], 200);
    }

    public function update(CategoryUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->validated();
    
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

       \Log::debug('بيانات التحديث من الفورم:', $data); 
            $category = $this->categoryService->updateCategory($id, $data);
    
            return response()->json([
                'status' => 'success',
                'message' => 'تم تعديل الفئة بنجاح.',
                'data' => $category,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تعديل الفئة: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    


    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'يرجى إدخال كلمة للبحث.'
            ], 400);
        }

        $results = $this->categoryService->search($query);

        if ($results->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'لم يتم العثور على نتائج مطابقة.',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم العثور على النتائج المطابقة.',
            'data' => $results
        ]);
    }
}
