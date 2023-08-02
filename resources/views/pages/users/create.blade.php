@extends('layouts.app')

@section('content')
<div class="content-header">
    <!-- ... -->
</div>

<div class="content">
    <!-- ... -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
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
                        <label for="alamat">Address:</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
                        <span id="alamat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Phone:</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" required>
                        <span id="telepon_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Date of Birth:</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" required>
                        <span id="tgl_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tmpt_lahir">Place of Birth:</label>
                        <input type="text" name="tmpt_lahir" id="tmpt_lahir" class="form-control" required>
                        <span id="tmpt_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_induk">Registration Number:</label>
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
                        <select name="role" id="role" class="form-control" required>
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
    $("#createUserForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#createUserBtn');
        btn.attr('disabled', true);
        btn.text("Loading...");
        // Resetting the error messages
        $('.text-danger').text('');

        let formData = new FormData(this);
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