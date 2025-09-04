@extends('adminlte::page')

@section('title', 'Courses')

@section('content')
<div class="container-fluid">

  <div class="card shadow-sm border-0 w-100" style="margin-top: 20px;"> {{-- أضفت margin-top هنا --}}
    <div class="card-header bg-info text-white">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h1 class="m-0"><i class="fas fa-book-open"></i> Courses</h1>
          <p class="text-white-50 mb-0">List of all courses</p>
        </div>
        <form action="{{ route('courses.search') }}" method="GET" class="d-flex mt-2 mt-md-0" role="search">
          <input 
            type="text" 
            name="query" 
            value="{{ request('query') }}" 
            class="form-control me-2" 
            placeholder="Search by course name"
            autocomplete="off"
          >
          <button type="submit" class="btn btn-light text-info">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </div>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover table-bordered mb-0 w-100">
        <thead class="table-info text-center">
          <tr>
            <th>Course Name</th>
            <th>Teacher</th>
            <th>Price</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
            <th>Videos</th>
            <th>Students</th>
            <th>Exam</th>
          </tr>
        </thead>
        <tbody>
          @forelse($courses as $course)
            <tr class="text-center align-middle" data-course-id="{{ $course->id }}">
              <td>{{ $course->name }}</td>
              <td>
                @if($course->teacher)
                  {{ $course->teacher->first_name ?? '' }} {{ $course->teacher->last_name ?? '' }}
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>{{ $course->price }}</td>
              <td>{{ Str::limit($course->description, 50) }}</td>
              <td class="status-cell">
                @if($course->accepted === 1)
                  <span class="badge bg-success">Accepted</span>
                @elseif($course->accepted === 0)
                  <span class="badge bg-danger">Rejected</span>
                @else
                  <span class="badge bg-warning">Pending</span>
                @endif
              </td>
              <td class="action-cell">
                @if(is_null($course->accepted))
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success btn-sm accept-btn" title="Accept">
                      <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm reject-btn" title="Reject">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                @elseif($course->accepted === 1)
                  <button class="btn btn-danger btn-sm reject-btn" title="Reject">
                    <i class="fas fa-times"></i>
                  </button>
                @elseif($course->accepted === 0)
                  <button class="btn btn-success btn-sm accept-btn" title="Accept">
                    <i class="fas fa-check"></i>
                  </button>
                @endif
              </td>
              <td>
                <a href="{{ route('courses.videos.view', $course->id) }}" class="btn btn-primary btn-sm" title="View Videos">
                  <i class="fas fa-video"></i> View Videos
                </a>
              </td>
              <td>
                <a href="{{ route('courses.students', $course->id) }}" class="btn btn-info btn-sm mt-1" title="View Students">
                  <i class="fas fa-users"></i> View Students
                </a>
              </td>
              <td>
                <button 
                  class="btn btn-warning btn-sm view-exam-btn" 
                  data-course-id="{{ $course->id }}" 
                  title="View Exam"
                >
                  <i class="fas fa-file-alt"></i> View Exam
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-muted">
                <i class="fas fa-info-circle"></i> No courses found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="d-flex justify-content-center mt-4">
    {{ $courses->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>

</div>

<!-- Exam Details Modal -->
<div class="modal fade" id="examModal" tabindex="-1" aria-labelledby="examModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="examModalLabel">Exam Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="examContent">
          <p class="text-muted">Loading exam details...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = '{{ csrf_token() }}';

    function updateCourseStatus(row, status) {
        const id = row.dataset.courseId;
        const url = status === 'accept' ? `/courses/${id}/accept` : `/courses/${id}/reject`;
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                let statusCell = row.querySelector('.status-cell');
                let actionCell = row.querySelector('.action-cell');
                if (status === 'accept') {
                    statusCell.innerHTML = '<span class="badge bg-success">Accepted</span>';
                    actionCell.innerHTML = `<button class="btn btn-danger btn-sm reject-btn" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>`;
                } else {
                    statusCell.innerHTML = '<span class="badge bg-danger">Rejected</span>';
                    actionCell.innerHTML = `<button class="btn btn-success btn-sm accept-btn" title="Accept">
                                                <i class="fas fa-check"></i>
                                            </button>`;
                }
            } else {
                alert(data.message || 'حدث خطأ ما');
            }
        })
        .catch(() => alert('حدث خطأ في الاتصال بالخادم'));
    }

    document.querySelector('table').addEventListener('click', async (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        const row = btn.closest('tr');

        if (btn.classList.contains('accept-btn')) {
            updateCourseStatus(row, 'accept');
            return;
        } 
        if (btn.classList.contains('reject-btn')) {
            updateCourseStatus(row, 'reject');
            return;
        }

        if (btn.classList.contains('view-exam-btn')) {
            const courseId = btn.dataset.courseId;
            const examModalElement = document.getElementById('examModal');
            const examModal = new bootstrap.Modal(examModalElement);
            const examContent = document.getElementById('examContent');

            examContent.innerHTML = '<p class="text-muted">Loading exam details...</p>';
            examModal.show();

            try {
                const res = await fetch(`/courses/${courseId}/exams`);
                if (!res.ok) {
                    const err = await res.json();
                    examContent.innerHTML = `<p class="text-danger">${err.message || 'Failed to load exam.'}</p>`;
                    return;
                }
                const data = await res.json();

                if (!data.exam) {
                    examContent.innerHTML = '<p class="text-muted">No exam details available.</p>';
                    return;
                }

                let html = `<h5>Duration: ${data.exam.duration_minutes} minutes</h5>`;
                html += `<p>Total Questions: ${data.exam.questions.length}</p>`;
                html += '<ol>';

                data.exam.questions.forEach((q) => {
                    html += `<li><strong>${q.question_text}</strong>`;
                    if (q.choices && q.choices.length) {
                        html += '<ul>';
                        q.choices.forEach(choice => {
                            const correctMark = choice.is_correct ? 
                                ' <span class="badge bg-success">✔ Correct</span>' : '';
                            html += `<li>${choice.choice_text}${correctMark}</li>`;
                        });
                        html += '</ul>';
                    }
                    html += '</li>';
                });

                html += '</ol>';
                examContent.innerHTML = html;

            } catch (error) {
                examContent.innerHTML = '<p class="text-danger">Error loading exam data.</p>';
            }
        }
    });
});
</script>
@endpush
