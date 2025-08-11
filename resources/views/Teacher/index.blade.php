@extends('adminlte::page')

@section('title', 'Teachers')

@section('content')
<div class="container-fluid">

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

  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm border-0 w-100">
        <div class="card-header bg-info text-white">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
              <h1 class="m-0"><i class="fas fa-chalkboard-teacher"></i> Teacher Management</h1>
              <p class="text-white-50 mb-0">View and manage all registered teachers</p>
            </div>
            <form action="{{ route('teachers.search') }}" method="GET" class="d-flex mt-2 mt-md-0">
              <input type="text" name="query" value="{{ request('query') }}" class="form-control me-2" placeholder="Search by name">
              <button type="submit" class="btn btn-light text-info">
                <i class="fas fa-search"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm border-0 w-100">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-bordered mb-0 w-100">
          <thead class="table-info text-center">
            <tr>
              <th>Name</th>
              <th>Specialization</th>
              <th>Status</th>
              <th>Email</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($teachers as $teacher)
              <tr class="text-center align-middle">
                <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                <td>{{ $teacher->specialization }}</td>
                <td>
                  <span class="badge bg-{{ $teacher->status === 'approved' ? 'success' : ($teacher->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($teacher->status) }}
                  </span>
                </td>
                <td>{{ $teacher->user->email }}</td>
                <td>
                  <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $teacher->id }}">
                    <i class="fas fa-eye"></i> 
                  </button>

                  @if($teacher->status === 'pending')
                    <div class="btn-group mt-1" role="group">
                      <form action="{{ route('teachers.approve', $teacher->id) }}" method="POST" class="d-inline">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-check"></i></button>
                      </form>
                      <form action="{{ route('teachers.reject', $teacher->id) }}" method="POST" class="d-inline">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-times"></i></button>
                      </form>
                    </div>
                  @endif

                  <div class="modal fade" id="detailsModal{{ $teacher->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $teacher->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                          <h5 class="modal-title" id="detailsModalLabel{{ $teacher->id }}">
                            Teacher Details - {{ $teacher->first_name }} {{ $teacher->last_name }}
                          </h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="row g-3">
                            <div class="col-12"><strong>Full Name:</strong> {{ $teacher->first_name }} {{ $teacher->last_name }}</div>
                            <div class="col-12"><strong>Email:</strong> {{ $teacher->user->email }}</div>
                            <div class="col-12"><strong>Phone:</strong> {{ $teacher->phone ?? '-' }}</div>
                            <div class="col-12"><strong>Specialization:</strong> {{ $teacher->specialization }}</div>
                            <div class="col-12"><strong>Country:</strong> {{ $teacher->country ?? '-' }}</div>
                            <div class="col-12"><strong>City:</strong> {{ $teacher->city ?? '-' }}</div>
                            <div class="col-12"><strong>Gender:</strong> {{ $teacher->gender === 'male' ? 'Male' : 'Female' }}</div>
                            <div class="col-12"><strong>Previous Experience:</strong><br>{{ $teacher->Previous_experiences ?? '-' }}</div>
                            <div class="col-12"><strong>Status:</strong>
                              <span class="badge bg-{{ $teacher->status === 'approved' ? 'success' : ($teacher->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($teacher->status) }}
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
                <td colspan="5" class="text-center text-muted">
                  <i class="fas fa-info-circle"></i> No teachers found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-center mt-4">
    {{ $teachers->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>

</div>
@endsection

@push('js')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
