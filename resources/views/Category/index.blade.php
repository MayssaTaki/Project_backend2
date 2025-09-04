@extends('adminlte::page')

@section('title', 'Categories')

@section('content')
<div class="container-fluid">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@if ($errors->any())
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>يوجد بعض المشاكل في البيانات المُدخلة:</strong>
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
      <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
              <h1 class="m-0">
                <i class="fas fa-tags"></i> Category Management
              </h1>
              <p class="text-white-50 mb-0">Manage and review all categories</p>
            </div>
            <form action="{{ route('categories.search') }}" method="GET" class="d-flex">
              <input type="text" name="query" class="form-control me-2" placeholder="Search for a category..." value="{{ request('query') }}">
              <button type="submit" class="btn btn-info text-white">
                <i class="fas fa-search"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-12 text-end">
      <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="fas fa-plus"></i> Create Category
      </button>
    </div>
  </div>

  <div class="card shadow-lg border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center">
          <thead class="table-info">
            <tr>
              <th>Category Name</th>
              <th>Image</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $category)
            <tr>
              <td>{{ $category->name }}</td>
              <td>
                @if($category->image)
                  <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="rounded-circle shadow" style="width: 40px; height: 40px; object-fit: cover;">
                @else
                  <span class="text-muted">No image</span>
                @endif
              </td>
              <td>
                <button
                  class="btn btn-outline-primary btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#editCategoryModal"
                  data-id="{{ $category->id }}"
                  data-name="{{ $category->name }}"
                  data-image="{{ $category->image ? asset($category->image) : '' }}"
                >
                  <i class="fas fa-edit"></i> 
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" class="text-center text-muted">
                <i class="fas fa-info-circle"></i> No categories found.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="d-flex justify-content-center">
      {{ $categories->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="createCategoryModalLabel"><i class="fas fa-plus-circle"></i> Create Category</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="name-create" class="form-label">Category Name <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name-create" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="image-create" class="form-label">Image <span class="text-danger">*</span></label>
          <input type="file" name="image" id="image-create" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-info text-white">Save</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" action="#" class="modal-content" id="editCategoryForm">
      @csrf
      @method('PUT')
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editCategoryModalLabel"><i class="fas fa-edit"></i> Edit Category</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="category_id" id="edit-category-id" value="0">

        <div class="mb-3">
          <label for="name-edit" class="form-label">Category Name <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name-edit" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="image-edit" class="form-label">Image (اختياري)</label>
          <input type="file" name="image" id="image-edit" class="form-control" accept="image/*">
          <div class="mt-2">
            <img src="" alt="Current Image" id="current-image" class="rounded-circle shadow" style="width: 60px; height: 60px; object-fit: cover; display: none;">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary text-white">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
  const editModal = document.getElementById('editCategoryModal');
  editModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;

    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const image = button.getAttribute('data-image');

    document.getElementById('edit-category-id').value = id;
    document.getElementById('name-edit').value = name;
    const currentImage = document.getElementById('current-image');

    if(image){
      currentImage.src = image;
      currentImage.style.display = 'inline-block';
    } else {
      currentImage.style.display = 'none';
    }

    const form = document.getElementById('editCategoryForm');
    form.action = `/categories/${id}`;  
  });
</script>
@endsection
