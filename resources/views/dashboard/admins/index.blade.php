@extends('layouts.master')
@section('title')
    Admins
@endsection


@section('pagetitle1')
    Dashboard
@endsection


@section('pagetitle2')
    Admins
@endsection

@section('pagetitle3')
    All Admins
@endsection

@section('content')
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Admin</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Admin?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal HTML -->
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Admin</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAdminForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Permission: <span style="color: red;">*</span></label>
                        <select class="form-control" name="admin" id="adminRolesDropdown">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editAdminName">Admin Name: <span style="color: red;">*</span></label>
                        <input type="text" name="admin_name" class="form-control" id="editAdminName">
                        <span id="edit_admin_name-error" style="color: red;"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: <span style="color: red;">*</span></label>
                        <input type="password" name="password" class="form-control" id="password">
                        <span id="password-error" style="color: red;"></span>
                    </div>
                    <button type="submit" class="btn btn-primary" id="updateAdminBtn">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add new Admin</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="adminForm" action="{{ route('admins.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Permission : <span style="color: red;">*</span></label>
                            <select class="form-control" name="admin" required>
                                <option value="" disabled>Select a role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <span id="admin-error" style="color: red;"></span> <!-- Error message span -->
                        </div>

                        <div class="form-group">
                            <label for="adminName">Admin Name: <span style="color: red;">*</span></label>
                            <input type="text" name="admin_name" class="form-control" id="adminName">
                            <span id="admin_name-error" style="color: red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password: <span style="color: red;">*</span></label>
                            <input type="password" name="password" class="form-control" id="password">
                            <span id="password-error" style="color: red;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveAdminBtn">Save</button>
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
            <h3 class="card-title">All Admins</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                @permission('users-create')
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-default">
                        New Admin
                    </button>
                @endpermission
                <thead>
                    <tr style="text-align: center;">
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Permission</th>
                        @permission('users-update')
                            <th>Active</th>
                        @endpermission
                        <th>Created date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr style="text-align: center;">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                <span class="badge bg-success">{{ $user->userRoles->pluck('name')->join(', ') }}</span>
                            </td>
                            @permission('users-update')
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input toggle-active-checkbox"
                                            id="exampleCheck{{ $user->id }}" data-id="{{ $user->id }}"
                                            @if ($user->active == 1) checked @endif>
                                        <label class="form-check-label" for="exampleCheck{{ $user->id }}"></label>
                                    </div>
                                </td>
                            @endpermission


                            <td>{{ $user->created_at->format('m/d/y g:i A') }}</td>
                            <th>
                                @permission('users-update')
                                    <a href="#" class="edit-admin" data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-roles="{{ json_encode($user->userRoles->pluck('id')) }}"
                                        ><i class="btn btn-primary btn-sm">Edit</i></a>
                                @endpermission
                                @permission('users-delete')
                                    <form id="deleteForm" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $user->id }})">Delete</button>
                                    </form>
                                @endpermission



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
            let adminId = $(this).data('id');
            let isChecked = $(this).is(':checked') ? 1 : 0;

            // Make AJAX request to update the status
            $.ajax({
                url: '/admins/' + adminId + '/toggle-active',
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
        let deleteAdminId;

        function confirmDelete(id) {
            deleteAdminId = id;
            $('#delete-modal').modal('show'); // Show the confirmation modal
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            $.ajax({
                url: `/admins/${deleteAdminId}`,
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
                        window.location.href = '{{ route('admins.index') }}';
                    });
                },
                error: function(xhr) {
                    console.error('Delete failed:', xhr);
                    $('#delete-modal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while trying to delete the Admin.'
                    });
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit-admin', function() {
            const adminId = $(this).data('id');
            const adminName = $(this).data('name');
            const adminRoleId = $(this).data('role-id'); // Assuming you pass the role ID as data
            const adminPassword = $(this).data('password');
             const adminRoles = $(this).data('roles');

            $('#editAdminName').val(adminName);
            $('#password').val(adminPassword);
            $('#admin').val(adminRoleId); // Set the selected role
            $('#editAdminForm').attr('action', '/admins/' + adminId);
             $('#editAdminForm select[name="admin"] option').each(function() {
        $(this).prop('selected', adminRoles.includes(parseInt($(this).val())));
    });
            $('#edit-modal').modal('show');
        });

        document.getElementById('editAdminForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let updateButton = document.getElementById('updateAdminBtn');
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
                        window.location.href = '{{ route('admins.index') }}';
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
        document.getElementById('adminForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let saveButton = document.getElementById('saveAdminBtn');
            saveButton.disabled = true;


            document.querySelectorAll('span[id$="-error"]').forEach(span => span.textContent = '');

            $.ajax({
                url: '{{ route('admins.store') }}',
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
                        window.location.href = '{{ route('admins.index') }}';
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
