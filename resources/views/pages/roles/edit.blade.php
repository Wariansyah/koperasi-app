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
    <!-- ... -->
</div>
@endsection

@section('script')
<script type="application/javascript">
    $("#editRoleForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#editRoleBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        let formData = new FormData(this);
        $('#name_error').text('');

        $.ajax({
            url: $(this).attr('action'),
            type: "POST", // Change this to PUT if needed
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                $(".preloader").fadeOut();
                if (response.success) { // Check if the success property is true
                    window.location.href = "{{ route('roles.index') }}";
            },
            
        });
    });
</script>
@endsection
  