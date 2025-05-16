@extends('adminlte::page')

@section('title', 'Students')

@section('content')
<div class="container-fluid">

  {{-- Validation Errors --}}
  @if ($errors->any())
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      <strong>There were some issues with your input:</strong>
      <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- Page Title + Search --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm border-0 w-100">
        <div class="card-header bg-info text-white">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
              <h1 class="m-0"><i class="fas fa-user-graduate"></i> Student Management</h1>
              <p class="text-white-50 mb-0">View and manage all registered students</p>
            </div>
            <form action="{{ route('students.search') }}" method="GET" class="d-flex mt-2 mt-md-0">
              <input type="text" name="query" value="{{ request('query') }}" class="form-control me-2" placeholder="Search by name">
              <button type="submit" class="btn btn-light text-primary">
                <i class="fas fa-search"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Students Table --}}
  <div class="card shadow-sm border-0 w-100">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-bordered mb-0 w-100">
          <thead class="table-primary text-center">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Gender</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($students as $student)
              <tr class="text-center align-middle">
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                <td>{{ $student->user->email }}</td>
                <td>{{ $student->phone ?? '-' }}</td>
                <td>{{ $student->gender === 'male' ? 'Male' : 'Female' }}</td>
                <td>
                  <span class="badge bg-{{ $student->is_banned ? 'secondary' : 'success' }}">
                    {{ $student->is_banned ? 'Inactive' : 'Active' }}
                  </span>
                </td>
                <td>
                  {{-- View Button --}}
                  <button type="button" class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $student->id }}">
                    <i class="fas fa-eye"></i>
                  </button>

                  {{-- Block/Unblock Button --}}
                  @if (!$student->is_banned)
                    <form action="{{ route('students.block', $student->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-info btn-sm mb-1" onclick="return confirm('Are you sure you want to block this student?')">
                        <i class="fas fa-user-slash"></i> 
                      </button>
                    </form>
                  @else
                    <form action="{{ route('students.unblock', $student->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-info btn-sm mb-1" onclick="return confirm('Are you sure you want to unblock this student?')">
                        <i class="fas fa-user-check"></i>
                      </button>
                    </form>
                  @endif

                  {{-- Modal --}}
                  <div class="modal fade" id="detailsModal{{ $student->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $student->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                          <h5 class="modal-title" id="detailsModalLabel{{ $student->id }}">
                            Student Details - {{ $student->first_name }} {{ $student->last_name }}
                          </h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="row g-3">
                            <div class="col-12"><strong>Full Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</div>
                            <div class="col-12"><strong>Email:</strong> {{ $student->user->email }}</div>
                            <div class="col-12"><strong>Phone:</strong> {{ $student->phone ?? '-' }}</div>
                            <div class="col-12"><strong>Country:</strong> {{ $student->country ?? '-' }}</div>
                            <div class="col-12"><strong>City:</strong> {{ $student->city ?? '-' }}</div>
                            <div class="col-12"><strong>Gender:</strong> {{ $student->gender === 'male' ? 'Male' : 'Female' }}</div>
                            <div class="col-12"><strong>Status:</strong>
                              <span class="badge bg-{{ $student->is_banned ? 'secondary' : 'success' }}">
                                {{ $student->is_banned ? 'Inactive' : 'Active' }}
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">
                  <i class="fas fa-info-circle"></i> No students found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Pagination --}}
  <div class="d-flex justify-content-center mt-4">
    {{ $students->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>

</div>
@endsection

@push('js')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
