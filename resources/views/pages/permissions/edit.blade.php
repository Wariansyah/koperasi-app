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
                <form id="editPermissionForm" method="POST" action="{{ route('permissions.update', $permission->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Permission Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="editPermissionBtn" class="btn btn-primary">Update Permission</button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
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
    $("#editPermissionForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#editPermissionBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        let formData = new FormData(this);
        $('#name_error').text('');

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                $(".preloader").fadeOut();
                if (response.success) {
                    window.location.href = "{{ route('permissions.index') }}";
                    sessionStorage.setItem('success', response.message);
                } else {
                    btn.attr('disabled', false);
                    btn.val("Update Permission");
                    $('#name_error').text(response.errors.name[0]);
                }
            },
            error: function(response) {
                btn.attr('disabled', false);
                btn.val("Update Permission");
                $('#name_error').text(response.responseJSON.errors.name);
            }
        });
    });
</script>

@endsection

