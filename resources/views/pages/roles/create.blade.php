@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create New Role</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createRoleForm" method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Role Name:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br />
                        <div class="container">
                            @foreach($permission as $value)
                            <div class="form-check">
                                <input class="form-check-input" value="{{ $value->id }}" name="permission[]" type="checkbox" id="flexSwitchCheckDefault{{ $value->id }}">
                                <label class="form-check-label" for="flexSwitchCheckDefault{{ $value->id }}">{{ $value->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createRoleBtn" class="btn btn-primary">Create Role</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="application/javascript">
    $(document).ready(function() {
        $("#createRoleForm").on('submit', function(e) {
            e.preventDefault();
            var btn = $('#createRoleBtn');
            btn.attr('disabled', true);
            btn.html("Loading...");
            var formData = new FormData(this);
            $('#name_error').text('');
            $('#permission_error').text('');

            if ($('#name').val() === '') {
                $('#name_error').text('Role Name harus diisi.');
                btn.attr('disabled', false);
                btn.html("Create Role");
                return;
            }

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Role berhasil dibuat',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "{{ route('roles.index') }}";
                        });
                    } else {
                        btn.attr('disabled', false);
                        btn.val("Create Role");
                        if (response.errors) {
                            if (response.errors.name) {
                                $('#name_error').text(response.errors.name[0]);
                            }
                            if (response.errors.permission) {
                                $('#permission_error').text(response.errors.permission[0]);
                            }
                        }
                    }
                },
                error: function(xhr, status, error) {
                    btn.attr('disabled', false);
                    btn.val("Create Role");
                }
            });
        });
    });
</script>

@endsection