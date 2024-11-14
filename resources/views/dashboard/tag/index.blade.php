@extends('layouts.master')
@section('title')
    Tags
@endsection


@section('pagetitle1')
    Dashboard
@endsection


@section('pagetitle2')
    Tag
@endsection

@section('pagetitle3')
    All Tags
@endsection

@section('content')
  
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Tags</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Active</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td>
                                <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input toggle-active-checkbox"
                                        id="exampleCheck{{ $tag->id }}" data-id="{{ $tag->id }}"
                                        @if ($tag->active == 1) checked @endif>
                                    <label class="form-check-label" for="exampleCheck{{ $tag->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $tag->created_at->format('m/d/y g:i A') }}</td>
                            
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
            let tagId = $(this).data('id');
            let isChecked = $(this).is(':checked') ? 1 : 0;

            // Make AJAX request to update the status
            $.ajax({
                url: '/tags/' + tagId + '/toggle-active',
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
        let deletetagId;

        function confirmDelete(id) {
            deletetagId = id;
            $('#delete-modal').modal('show'); // Show the confirmation modal
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            $.ajax({
                url: `/tags/${deletetagId}`,
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
                        window.location.href = '{{ route('tags.index') }}';
                    });
                },
                error: function(xhr) {
                    console.error('Delete failed:', xhr);
                    $('#delete-modal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while trying to delete the tag.'
                    });
                }
            });
        });
    </script>

@endsection
