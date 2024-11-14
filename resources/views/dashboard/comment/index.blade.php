@extends('layouts.master')
@section('title')
    Comments
@endsection


@section('pagetitle1')
    Dashboard
@endsection


@section('pagetitle2')
    Comment
@endsection

@section('pagetitle3')
    All Comments
@endsection

@section('content')
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Comment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Comment?</p>
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
            <h3 class="card-title">All comments</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Comment</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Approve</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>{{ $comment->comment }}</td>
                            <td>{{ $comment->name }}</td>
                            <td>{{ $comment->email }}</td>
                        
                            <td>{{ $comment->created_at->format('m/d/y g:i A') }}</td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input toggle-active-checkbox"
                                        id="exampleCheck{{ $comment->id }}" data-id="{{ $comment->id }}"
                                        @if ($comment->active == 1) checked @endif>
                                    <label class="form-check-label" for="exampleCheck{{ $comment->id }}"></label>
                                </div>
                            </td>
                            <th>
                                {{-- <a href="#" class="edit-comment" data-id="{{ $comment->id }}"
                                    data-name="{{ $comment->name }}"><i class="btn btn-primary btn-sm">Edit</i></a> --}}

                                <form id="deleteForm" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $comment->id }})">Delete</button>
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
        let deletecommentId;

        function confirmDelete(id) {
            deletecommentId = id;
            $('#delete-modal').modal('show'); // Show the confirmation modal
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            $.ajax({
                url: `/comments/${deletecommentId}`,
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
                        window.location.href = '{{ route('comments.index') }}';
                    });
                },
                error: function(xhr) {
                    console.error('Delete failed:', xhr);
                    $('#delete-modal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while trying to delete the comment.'
                    });
                }
            });
        });
    </script>

     <script>
        $(document).on('change', '.toggle-active-checkbox', function() {
            let commentId = $(this).data('id');
            let isChecked = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '/comments/' + commentId + '/toggle-active',
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

@endsection
