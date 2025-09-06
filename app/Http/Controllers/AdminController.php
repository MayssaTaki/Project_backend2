<?php

namespace App\Http\Controllers;
use App\Services\CategoryService;
use App\Services\TeacherService;
use App\Services\StudentService;
use App\Services\CourseService;
use App\Services\ExamService;
use App\Services\WalletService;


use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\Teacher;
use App\Models\Course;

use Illuminate\Http\Request;
class AdminController extends Controller
{
    protected $categoryService;
protected $teacherService;
protected $studentService;
protected $courseService;

    public function __construct(CategoryService $categoryService
    ,TeacherService $teacherService,StudentService $studentService,
    CourseService $courseService,ExamService $examService,WalletService $walletService)
    {
        $this->categoryService = $categoryService;
                $this->teacherService = $teacherService;
                  $this->studentService = $studentService;
                  $this->courseService = $courseService;
                  $this->examService = $examService;
                  $this->walletService = $walletService;


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
      foreach ($teachers as $teacher) {
        $teacher->wallet_balance = $this->walletService->getBalance($teacher);
    }
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
          foreach ($students as $student) {
        $student->wallet_balance = $this->walletService->getBalance($student);
    }
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

 public function indexCourses()
{
    $courses = $this->courseService->getAll();
    return view('courses.index', compact('courses'));
}

 public function acceptCourse($id, Request $request)
    {
        try {
            $course = $this->courseService->acceptCourse($id, $request->user());

            return response()->json([
                'status' => 'success',
                'message' => 'تم قبول الكورس بنجاح.',
                'course' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function rejectCourse($id, Request $request)
    {
        try {
            $course = $this->courseService->rejectCourse($id, $request->user());

            return response()->json([
                'status' => 'success',
                'message' => 'تم رفض الكورس بنجاح.',
                'course' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }

  public function searchCoursesByName(Request $request)
{
    $request->validate([
        'query' => 'nullable|string|min:2'
    ]);

    $query = $request->input('query');

    $courses = $this->courseService->searchCoursesByName($query);

    return view('courses.index', compact('courses'));
}
public function showVideos($courseId)
{
    $course = Course::findOrFail($courseId);
    $videos = $course->videos; 
    
    return view('courses.course-videos', compact('course', 'videos'));
}

public function showRegisteredStudents($courseId)
    {
        $students = $this->courseService->getStudentsByCourse($courseId);

        return view('courses.registered_students', compact('students', 'courseId'));
    }


public function getExamByCourse($courseId)
{
    $exam = $this->examService->getExamQuestionsWithChoicesByCourseId($courseId);

    if (!$exam) {
        return response()->json([
            'message' => 'لا يوجد امتحان مرتبط بهذا الكورس.',
        ], 404);
    }

    return response()->json([
        'message' => 'تم جلب الامتحان بنجاح.',
        'exam' => $exam,
    ]);
}


}





