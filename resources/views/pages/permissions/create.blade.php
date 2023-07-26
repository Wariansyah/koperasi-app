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
                    <form id="createRoleForm" method="POST" action="{{ route('permissions.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Permission Name:</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            <span id="name_error" class="text-danger"></span>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" id="createRoleBtn" class="btn btn-primary">Create Role</button>
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
    $("#create-user").on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btn-create');
            btn.attr('disabled', true);
            btn.val("Loading...");
            let formData = new FormData(this);
            $('#name_error').text('');

            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('permissions.store') }}",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $(".preloader").fadeOut();
                    if (response.success) {
                        window.location.href = "{{ route('permissions.index') }}";
                        sessionStorage.setItem('success', response.message);
                    }
                },
                error: function(response) {
                    btn.attr('disabled', false);
                    btn.val("Simpan");
                    $('#name_error').text(response.responseJSON.errors.name);
                }
            });

        });
    </script>
@endsection
