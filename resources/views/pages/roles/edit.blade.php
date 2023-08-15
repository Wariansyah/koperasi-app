@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Role</h1>
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
                <form id="editRoleForm" method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Role Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br />
                        <div class="container">
                            @foreach($permission as $value)
                            <div class="form-check">
                                <input class="form-check-input" value="{{ $value->id }}" name="permission[]" type="checkbox" id="flexSwitchCheckDefault{{ $value->id }}" @if(in_array($value->id, $rolePermissions)) checked @endif>
                                <label class="form-check-label" for="flexSwitchCheckDefault{{ $value->id }}">{{ $value->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="editRoleBtn" class="btn btn-primary">Update Role</button>
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
    $("#editRoleForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#editRoleBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('#name_error').text('');
        $('#permission_error').text('');

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
                        title: 'Role berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "{{ route('roles.index') }}";
                    });
                } else {
                    btn.attr('disabled', false);
                    btn.val("Update Role");
                    if (response.errors) {
                        if (response.errors.name) {
                            $('#name_error').text(response.errors.name[0]);
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                btn.attr('disabled', false);
                btn.val("Update Role"); 
                if (xhr.status === 422) {
                    var errors = JSON.parse(xhr.responseText).errors;
                        if (errors.name) {
                            $('#name_error').text(errors.name[0]);
                        }       
                }
            }

        });
        $('#name_error').on('input', function(){
            $('#name_error').text('');
        });
    });
</script>
@endsection