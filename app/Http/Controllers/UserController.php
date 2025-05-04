<?php

namespace App\Http\Controllers;
use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->userService->update($user, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث بيانات المستخدم بنجاح.',
                'data' => $updatedUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء التحديث: ' . $e->getMessage()
            ], 500);
        }
    }








    public function getAllUsers()
    {
        $users = $this->userService->getAllUsers();
    
        if ($users->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تم استرجاع جميع المستخدمين بنجاح',
                'data' => $users
            ], 200); 
        }
    
        return response()->json([
            'status' => 'fail',
            'message' => 'لم يتم العثور على مستخدمين',
            'data' => []
        ], 404); 
    }
    
    

    public function search(SearchUserRequest $request)
    {
        $firstName = $request->input('first_name');

        $users = $this->userService->searchUserByFirstName($firstName);
        
        if ($users->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تم العثور على المستخدمين',
                'data' => $users
            ], 200);
        }
    
        return response()->json([
            'status' => 'fail',
            'message' => 'لم يتم العثور على مستخدمين بهذا الاسم',
            'data' => []
        ], 404);
    }
    
}
