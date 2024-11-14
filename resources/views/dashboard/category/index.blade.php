@extends('layouts.master')
@section('title')
    Categories
@endsection


@section('pagetitle1')
    Dashboard
@endsection


@section('pagetitle2')
    Category
@endsection

@section('pagetitle3')
    All Categories
@endsection

@section('content')
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this category?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="editCategoryName">Category Name: <span style="color: red;">*</span></label>
                            <input type="text" name="category_name" class="form-control" id="editCategoryName">
                            <span id="edit_category_name-error" style="color: red;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary" id="updateCategoryBtn">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add new category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="categoryName">Category Name: <span style="color: red;">*</span></label>
                            <input type="text" name="category_name" class="form-control" id="categoryName">
                            <span id="category_name-error" style="color: red;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveCategoryBtn">Save</button>
                    </form>

                </div>
                <div class="modal-footer justify-content-between">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All categories</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-default">
                    New Category
                </button>
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Active</th>
                        <th>Created date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input toggle-active-checkbox"
                                        id="exampleCheck{{ $category->id }}" data-id="{{ $category->id }}"
                                        @if ($category->active == 1) checked @endif>
                                    <label class="form-check-label" for="exampleCheck{{ $category->id }}"></label>
                                </div>
                            </td>


                            <td>{{ $category->created_at->format('m/d/y g:i A') }}</td>
                            <th>
                                <a href="#" class="edit-category" data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"><i class="btn btn-primary btn-sm">Edit</i></a>

                                <form id="deleteForm" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $category->id }})">Delete</button>
                                </form>



                            </th>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('scripts')
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <script>
        // Handle checkbox toggle
        $(document).on('change', '.toggle-active-checkbox', function() {
            let categoryId = $(this).data('id');
            let isChecked = $(this).is(':checked') ? 1 : 0;

            // Make AJAX request to update the status
            $.ajax({
                url: '/categories/' + categoryId + '/toggle-active',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    active: isChecked
                },
                success: function(response) {
                    if (response.success) {
                        // Show a success toast notification
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });

                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    }
                },
                error: function(xhr) {
                    // Handle error response
                    console.error('Status update failed:', xhr);
                }
            });
        });
    </script>


    <script>
        let deleteCategoryId;

        function confirmDelete(id) {
            deleteCategoryId = id;
            $('#delete-modal').modal('show'); // Show the confirmation modal
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            $.ajax({
                url: `/categories/${deleteCategoryId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Hide the modal after successful deletion
                    $('#delete-modal').modal('hide');

                    // Display a success toast with SweetAlert
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'center',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(() => {
                        // Redirect to the index page after showing the toast
                        window.location.href = '{{ route('categories.index') }}';
                    });
                },
                error: function(xhr) {
                    console.error('Delete failed:', xhr);
                    $('#delete-modal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while trying to delete the category.'
                    });
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit-category', function() {
            const categoryId = $(this).data('id');
            const categoryName = $(this).data('name');

            $('#editCategoryName').val(categoryName);
            $('#editCategoryForm').attr('action', '/categories/' + categoryId);
            $('#edit-modal').modal('show');
        });

        document.getElementById('editCategoryForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let updateButton = document.getElementById('updateCategoryBtn');
            updateButton.disabled = true;

            document.querySelectorAll('span[id$="-error"]').forEach(span => span.textContent = '');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: "success",
                        title: response.message
                    }).then(() => {
                        window.location.href = '{{ route('categories.index') }}';
                    });
                },
                error: function(xhr) {
                    saveButton.disabled = false;

                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '-error').text(value[0]);
                    });
                }
            });
        });
    </script>
    <script>
        document.getElementById('categoryForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let saveButton = document.getElementById('saveCategoryBtn');
            saveButton.disabled = true;


            document.querySelectorAll('span[id$="-error"]').forEach(span => span.textContent = '');

            $.ajax({
                url: '{{ route('categories.store') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });

                    Toast.fire({
                        icon: "success",
                        title: response.message
                    }).then(() => {
                        window.location.href = '{{ route('categories.index') }}';
                    });
                },
                error: function(xhr) {
                    saveButton.disabled = false;

                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '-error').text(value[0]);
                    });
                }
            });
        });
    </script>
@endsection
