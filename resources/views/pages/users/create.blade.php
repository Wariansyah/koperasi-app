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
                        <label for="address">Address:</label>
                        <input type="text" name="address" id="address" class="form-control" required>
                        <span id="address_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                        <span id="phone_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Date of Birth:</label>
                        <input type="date" name="birthdate" id="birthdate" class="form-control" required>
                        <span id="birthdate_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="birthplace">Place of Birth:</label>
                        <input type="text" name="birthplace" id="birthplace" class="form-control" required>
                        <span id="birthplace_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="registration_number">Registration Number:</label>
                        <input type="text" name="registration_number" id="registration_number" class="form-control" required>
                        <span id="registration_number_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <span id="gender_error" class="text-danger"></span>
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
<script type="application/javascript">
    $("#createUserForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#createUserBtn');
        btn.attr('disabled', true);
        btn.text("Loading...");
        let formData = new FormData(this);
        // Resetting the error messages
        $('.text-danger').text('');

        $.ajax({
            url: "{{ route('users.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = "{{ route('users.index') }}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(response) {
                btn.attr('disabled', false);
                btn.text("Create User");
                // Handling validation errors
                $.each(response.responseJSON.errors, function(key, value) {
                    $('#' + key + '_error').text(value[0]);
                });
            }
        });
    });
</script>
@endsection