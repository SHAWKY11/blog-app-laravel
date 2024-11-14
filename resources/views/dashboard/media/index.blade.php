@extends('layouts.master')
@section('title')
    Dashboard
@endsection


@section('pagetitle1')
    Dashboard
@endsection


@section('pagetitle2')
    media
@endsection

@section('pagetitle3')
    All Media
@endsection

@section('content')
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Media</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Media?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @foreach ($medias as $media)
                <div class="card col-md-3 mx-2 mb-3">
                    <div class="card-body">
                        <img src="{{ $media->image_path }}" style="width: 270px; heigh:134px;" class="img-thumbnail"
                            alt="">
                    </div>
                     <div class="card-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $media->id }})">
                        <i class="nav-icon fas fa-ban"></i>
                    </button>
                    <div class="ml-auto">
                        <button type="button" class="btn btn-primary btn-sm" onclick="copyLink('{{ $media->image_path }}')">
                            <i class="nav-icon fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function copyLink(link) {
            navigator.clipboard.writeText(link).then(() => {
                alert('Link copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
    <script>
        let deleteMediaId;

        function confirmDelete(id) {
            deleteMediaId = id;
            $('#delete-modal').modal('show'); // Show the confirmation modal
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            $.ajax({
                url: `/media/${deleteMediaId}`,
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
                        window.location.href = '{{ route('media.index') }}';
                    });
                },
                error: function(xhr) {
                    console.error('Delete failed:', xhr);
                    $('#delete-modal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while trying to delete the Media.'
                    });
                }
            });
        });
    </script>
@endsection
