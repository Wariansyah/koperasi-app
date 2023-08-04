@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Users</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <!-- ... -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createUserForm" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="id">ID:</label>
                        <input type="text" name="id" id="id" class="form-control" required>
                        <span id="id_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <span id="email_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span id="password_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
                        <span id="alamat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon:</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" required>
                        <span id="telepon_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir:</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" required>
                        <span id="tgl_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tmpt_lahir">Tempat Lahir:</label>
                        <input type="text" name="tmpt_lahir" id="tmpt_lahir" class="form-control" required>
                        <span id="tmpt_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_induk">Nomer Induk:</label>
                        <input type="text" name="no_induk" id="no_induk" class="form-control" required>
                        <span id="no_induk_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="jenkel">Gender:</label>
                        <select name="jenkel" id="jenkel" class="form-control" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <span id="jenkel_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select name="role_id" id="role" class="form-control" required>
                            <option value="">Select Role</option> <!-- Add this line for a default value -->
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <span id="role_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createUserBtn" class="btn btn-primary">Create User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ... -->
</div>
@endsection

@section('script')
<script type="text/javascript">
    $("#createUserBtn").on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        btn.attr('disabled', true);
        btn.text("Loading...");

        let form = $('#createUserForm')[0]; // Get the DOM element of the form
        let formData = new FormData(form); // Create a FormData object from the form data

        $.ajax({
            url: "{{ route('users.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                btn.attr('disabled', false);
                btn.text("Create User");
                if (response.success) {
                    // Redirect to the user list page after successful save
                    window.location.href = "{{ route('users.index') }}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                btn.attr('disabled', false);
                btn.text("Create User");
                // Handling validation errors
                if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    $.each(jqXHR.responseJSON.errors, function(key, value) {
                        $('#' + key + '_error').text(value[0]);
                    });
                }
            }
        });
    });
</script>
@endsection