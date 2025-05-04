<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

     public function studentCharge(Request $request)
     {
         $request->validate([
             'amount' => 'required|numeric|min:0.01',
         ]);
 
         $student = auth()->user()?->student;
 
         if (!$student) {
             return response()->json(['message' => 'هذا المستخدم ليس طالبًا.'], 403);
         }
 
         $wallet = $this->walletService->chargeStudent($student, $request->amount);
 
         return response()->json([
             'message' => 'تم شحن المحفظة بنجاح.',
             'balance' => $wallet->balance,
         ]);
     }
 
     public function studentBalance()
     {
         $student = auth()->user()?->student;
 
         if (!$student) {
             return response()->json(['message' => 'هذا المستخدم ليس طالبًا.'], 403);
         }
 
         $balance = $this->walletService->getBalance($student);
 
         return response()->json([
             'message' => 'رصيد الطالب الحالي.',
             'balance' => $balance,
         ]);
     }
 
     public function teacherBalance()
     {
         $teacher = auth()->user()?->teacher;
 
         if (!$teacher) {
             return response()->json(['message' => 'هذا المستخدم ليس أستاذًا.'], 403);
         }
 
         $balance = $this->walletService->getBalance($teacher);
 
         return response()->json([
             'message' => 'رصيد الأستاذ الحالي.',
             'balance' => $balance,
         ]);
     }
}
