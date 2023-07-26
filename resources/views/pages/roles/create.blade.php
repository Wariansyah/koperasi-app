@extends('layouts.app')

@section('content')
<div class="content-header">
    <!-- ... -->
</div>

<div class="content">
    <!-- ... -->
    <div class="col-md-8 offset-md-2">
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
                            <input class="form-check-input" value="{{ $value->id }}" name="permission[]" type="checkbox" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">{{ $value->name }}</label>
                            <br />
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
    $("#create-user").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#btn-create');
        btn.attr('disabled', true);
        btn.val("Loading...");
        let formData = new FormData(this);
        $('#name_error').text('');
        $('#permission_error').text('');

        $.ajax({
            url: "{{ route('roles.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                $(".preloader").fadeOut();
                if (response.success) {
                    window.location.href = "{{ route('roles.index') }}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(response) {
                btn.attr('disabled', false);
                btn.val("Simpan");
                $('#name_error').text(response.responseJSON.errors.name);
                $('#premission_error').text(response.responseJSON.errors.permission);
            }
        });

    });
</script>
@endsection
