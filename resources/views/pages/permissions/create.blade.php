@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Permissions</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createPermissionForm" method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Permission Name:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createPermissionBtn" class="btn btn-primary">Create Permission</button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $("#createPermissionForm").on('submit', function(e) {
            e.preventDefault();
            var btn = $('#createPermissionBtn');
            btn.attr('disabled', true);
            btn.val("Loading...");
            var formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $('#name_error').text('');

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        window.location.href = "{{ route('permissions.index') }}";
                        sessionStorage.setItem('success', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    btn.attr('disabled', false);
                    btn.val("Create Permission");
                    if (xhr.status === 422) {
                        var errors = JSON.parse(xhr.responseText).errors;
                        if (errors.name) {
                            $('#name_error').text(errors.name[0]);
                        }
                    }
                }
            });
        });

        // Listen to input changes and clear validation message when input changes
        $('#name').on('input', function() {
            $('#name_error').text('');
        });
    });
</script>
@endsection
