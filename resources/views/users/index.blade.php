<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">

<style>
    .form-control-custom {
        border-radius: 6px !important;
        border: 1px solid #ced4da !important;
        padding: 10px 15px !important;
        height: auto !important;
        font-size: 0.9rem !important;
    }
    .form-control-custom:focus {
        border-color: #6861ce !important;
        box-shadow: 0 0 0 0.2rem rgba(104, 97, 206, 0.1) !important;
    }
    .modal-content { border-radius: 12px !important; border: none !important; }
    .modal-header { border-bottom: 1px solid #f1f1f1 !important; padding: 20px 25px !important; }
    .modal-footer { padding: 15px 25px 25px !important; }
    .btn-save { background-color: #6861ce !important; border-color: #6861ce !important; color: #fff !important; }
    
    /* Eye Icon Styling */
    .password-wrapper { position: relative; }
    .password-wrapper .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #adb5bd;
        z-index: 10;
    }
</style>

<x-app-layout>
<div class="container py-4">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1 text-primary">User Management</h3>
                <h6 class="op-7 mb-0 text-muted">Manage system users and access levels</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <button class="btn btn-primary btn-round px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus-circle me-1"></i> Add User
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="card card-round">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">User List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="myTable" class="display table table-hover">
                        <thead class="bg-light text-uppercase">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold">{{ $user->name }}</td>
                                <td class="text-primary">{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <div class="form-button-action">
                                        <button class="btn btn-link btn-info" title="View" data-bs-toggle="modal" data-bs-target="#viewModal{{ $user->id }}"><i class="fa fa-eye"></i></button>
                                        <button class="btn btn-link btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-link btn-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title fw-bold">User Information</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <small class="text-muted text-uppercase fw-bold">Full Name</small>
                                                <p class="fs-5 mb-0">{{ $user->name }}</p>
                                            </div>
                                            <div class="mb-0">
                                                <small class="text-muted text-uppercase fw-bold">Email Address</small>
                                                <p class="fs-5 text-primary mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Update Account</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="mb-2 fw-bold small">Full Name</label>
                                                    <input type="text" name="name" class="form-control form-control-custom" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="mb-2 fw-bold small">Email Address</label>
                                                    <input type="email" name="email" class="form-control form-control-custom" value="{{ $user->email }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-save px-4">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content border-top border-danger border-4">
                                        <div class="modal-body text-center p-4">
                                            <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                                            <h4 class="fw-bold">Remove User?</h4>
                                            <p class="text-muted small">This action cannot be undone for <b>{{ $user->name }}</b>.</p>
                                            <div class="d-flex gap-2 justify-content-center mt-4">
                                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">No</button>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger px-4 text-white">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">New User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">FULL NAME</label>
                        <input type="text" name="name" class="form-control form-control-custom" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">EMAIL ADDRESS</label>
                        <input type="email" name="email" class="form-control form-control-custom" placeholder="Enter email" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold mb-1">PASSWORD</label>
                            <div class="password-wrapper">
                                <input type="password" name="password" class="form-control form-control-custom pwd-input" placeholder="••••••••" required>
                                <i class="fas fa-eye toggle-password"></i>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold mb-1">CONFIRM</label>
                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" class="form-control form-control-custom pwd-input" placeholder="••••••••" required>
                                <i class="fas fa-eye toggle-password"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-save w-100 mb-2 py-2 fw-bold">Add Account</button>
                    <button type="button" class="btn btn-link text-danger w-100 text-decoration-none p-0" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

<script>
$(document).ready(function () {
    // DataTable Init
    $('#myTable').DataTable({
        pageLength: 5,
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
        "language": { "search": "", "searchPlaceholder": "Search..." }
    });

    // Password Toggle Logic
    $('.toggle-password').on('click', function() {
        const input = $(this).siblings('.pwd-input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
</x-app-layout>