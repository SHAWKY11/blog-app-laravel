@extends('layouts.master')
@section('title')
    Posts
@endsection


@section('pagetitle1')
    Dashboard
@endsection


@section('pagetitle2')
    Post
@endsection

@section('pagetitle3')
    All Posts
@endsection

@section('content')
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete post</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this post?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>




    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All posts</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">
                    New Post
                </a>

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th style="text-align: center;">Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Active</th>
                        <th>Comments</th>
                        <th>Views</th>
                        <th>Created date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('show.post', $post->id) }}" target="_blank">{{ $post->title }}</a>
                            </td>
                            <td>{{ $post->author }}</td>
                            <td>{{ $post->category->name }}</td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input toggle-active-checkbox"
                                        id="exampleCheck{{ $post->id }}" data-id="{{ $post->id }}"
                                        @if ($post->active == 1) checked @endif>
                                    <label class="form-check-label" for="exampleCheck{{ $post->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $post->comments_count }}</td>
                            <td>{{ $post->views }}</td>

                            <td>{{ $post->created_at->format('m/d/y g:i A') }}</td>
                            <th>
                                <a href="{{ route('posts.edit', $post->id) }}" class="edit-post"
                                    data-id="{{ $post->id }}" data-name="{{ $post->name }}"><i
                                        class="btn btn-primary btn-sm">Edit</i></a>

                                <form id="deleteForm" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $post->id }})">Delete</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Handle checkbox toggle
        $(document).on('change', '.toggle-active-checkbox', function() {
            let postId = $(this).data('id');
            let isChecked = $(this).is(':checked') ? 1 : 0;

            // Make AJAX request to update the status
            $.ajax({
                url: '/posts/' + postId + '/toggle-active',
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
        $(document).ready(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif
        });
    </script>

    <script>
        let deleteposttId;

        function confirmDelete(id) {
            deletepostId = id;
            $('#delete-modal').modal('show'); // Show the confirmation modal
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            $.ajax({
                url: `/posts/${deletepostId}`,
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
                        window.location.href = '{{ route('posts.index') }}';
                    });
                },
                error: function(xhr) {
                    console.error('Delete failed:', xhr);
                    $('#delete-modal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while trying to delete the post.'
                    });
                }
            });
        });
    </script>
@endsection