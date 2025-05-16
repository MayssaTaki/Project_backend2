<?php

namespace App\Http\Controllers;
use App\Services\CategoryService;
use App\Services\TeacherService;
use App\Services\StudentService;


use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class AdminController extends Controller
{
    protected $categoryService;
protected $teacherService;
protected $studentService;

    public function __construct(CategoryService $categoryService
    ,TeacherService $teacherService,StudentService $studentService)
    {
        $this->categoryService = $categoryService;
                $this->teacherService = $teacherService;
                  $this->studentService = $studentService;


    }

public function index()
{
    $categories = $this->categoryService->getAllCategories();
    
    return view('Category.index', [
        'categories' => $categories
    ]);
}
public function search(Request $request)
    {
        $query = $request->input('query');
   $categories = $this->categoryService->search($query);
        return view('Category.index',[
             'categories' => $categories
        ]);
    }
   public function store(CategoryStoreRequest $request)
{
    $data = $request->validated();
    $category = $this->categoryService->createCategory($data);

    return redirect()->route('categories.index')
        ->with('success', 'تم إنشاء التصنيف بنجاح.');
}
public function update(Request $request)
{
    $id = $request->input('category_id');
    $category = Category::findOrFail($id);
if($request->hasFile('image')){
    $image = $request->file('image');
    $imageName = time() . '.' . $image->getClientOriginalExtension();
    $image->move(public_path('uploads/categories'), $imageName);
    $category->image = 'uploads/categories/' . $imageName;
}


    $category->save();

    return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
}


public function getAllTeachers()
    {
        $teachers = $this->teacherService->getAllTeachers();
        return view('Teacher.index', [
        'teachers' => $teachers
    ]);
    }

    public function approve($id)
    {
        $teacher = $this->teacherService->approveTeacher($id);

    return redirect()->route('teachers.index')->with('success', 'Teacher approved successfully.');
    }

    public function reject($id)
{
    $this->teacherService->rejectTeacher($id);
    return redirect()->route('teachers.index')->with('success', 'Teacher rejected successfully.');
}
public function count()
{
    $teachers = [
        'pending' => Teacher::where('status', 'pending')->count(),
        'approved' => Teacher::where('status', 'approved')->count(),
        'rejected' => Teacher::where('status', 'rejected')->count(),
    ];
    $students = [
    'active' => \App\Models\Student::where('is_banned', 'false')->count(),
    'inactive' => \App\Models\Student::where('is_banned', 'true')->count()
];

    return view('welcome', compact('teachers','students'));
}


public function searchTeacher(Request $request)
    {
        $query = $request->input('query');
   $teachers = $this->teacherService->search($query);
        return view('Teacher.index',[
             'teachers' => $teachers
        ]);
    }


public function getAllStudents()
    {
        $students = $this->studentService->getAllStudent();
        return view('Student.index', [
        'students' => $students
    ]);
    }
public function searchStudent(Request $request)
    {
        $query = $request->input('query');
   $students = $this->studentService->search($query);
        return view('Student.index',[
             'students' => $students
        ]);
    }
     public function ban($id)
    {
        $student = $this->studentService->ban($id);
           return redirect()->route('students.index');

    }
    public function unban($id)
    {
        $student = $this->studentService->unban($id);
           return redirect()->route('students.index');

    }
}





