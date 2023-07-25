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
                        <div class="container" id="sortableContainer">
                            @foreach($permission as $value)
                            <input class="form-check-input" value="{{ $value->id }}" name="permission[]" type="checkbox" id="flexSwitchCheckDefault{{ $value->id }}">
                            <label class="form-check-label" for="flexSwitchCheckDefault{{ $value->id }}">{{ $value->name }}</label>
                            <br />
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createRoleBtn" class="btn btn-primary">SIMPAN</button>
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
<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery UI library -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script type="application/javascript">
    $("#createRoleForm").on('submit', function(e) {
        e.preventDefault();
        var form = e.target; // Get the form element
        var btn = $(form).find('#createRoleBtn'); // Find the button element inside the form
        btn.attr('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        let formData = new FormData(form);
        $('#name_error').text('');

        $.ajax({
            url: $(form).attr('action'),
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.code === 200) {
                    // Redirect to roles.index after successful data submission
                    window.location.href = "{{ route('roles.index') }}";
                }
            },
            error: function(response) {
                btn.attr('disabled', false);
                btn.text("SIMPAN");
                if (response.responseJSON && response.responseJSON.errors && response.responseJSON.errors.name) {
                    $('#name_error').text(response.responseJSON.errors.name[0]);
                } else {
                    // Handle other error scenarios if necessary
                    console.log(response);
                }
            }
        });
    });

    // Add sortable functionality
    $(document).ready(function() {
        $("#sortableContainer").sortable({
            // Add options here if needed
        });

        // Initialize Summernote editor
        $('#description').summernote({
            height: 200,
            // Add other options here if needed
        });
    });
</script>
@endsection