@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Roles</h1>
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
    <!-- ... -->
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
                            @foreach ($permission as $value)
                            <div class="form-check">
                                <input class="form-check-input" value="{{ $value->id }}" name="permission[]" type="checkbox" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault">{{ $value->name }}</label>
                                <br />
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
    <!-- ... -->
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

            // Add your custom validation logic here
            var name = formData.get('name');
            var permissions = formData.getAll('permission[]');

            // Perform validation checks
            var validationError = false;
            if (name.trim() === '') {
                $('#name_error').text('Role Name is required.');
                validationError = true;
            }
            if (permissions.length === 0) {
                $('#permission_error').text('At least one permission must be selected.');
                validationError = true;
            }

            if (validationError) {
                btn.attr('disabled', false);
                btn.html("Create Role");
                return; // Stop form submission
            }

            $.ajax({
                url: "{{ route('roles.store') }}",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        window.location.href = "{{ route('roles.index') }}";
                        sessionStorage.setItem('success', response.message);
                    }
                },
                error: function(response) {
                    btn.attr('disabled', false);
                    btn.html("Create Role");
                    $('#name_error').text(response.responseJSON.errors.name);
                    $('#permission_error').text(response.responseJSON.errors.permission);
                }
            });
        });
    });
</script>

@endsection