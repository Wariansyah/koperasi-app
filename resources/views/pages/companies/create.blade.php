@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Company</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Companies</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createCompanyForm" method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                        <span id="nama_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                        <span id="alamat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <span id="email_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon:</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" required>
                        <span id="telepon_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo:</label>
                        <input type="file" name="logo" id="logo" class="form-control-file">
                        <span id="logo_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createCompanyBtn" class="btn btn-primary">Create Company</button>
                        <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Sertakan library SweetAlert -->
<script type="application/javascript">
    $(document).ready(function() {
        $("#createCompanyForm").on('submit', function(e) {
            e.preventDefault();
            var btn = $('#createCompanyBtn');
            btn.attr('disabled', true);
            btn.html("Loading...");
            var formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $('#nama_error').text('');
            $('#alamat_error').text('');
            $('#email_error').text('');
            $('#telepon_error').text('');
            $('#logo_error').text('');

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
                            title: 'Company created successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "{{ route('companies.index') }}"; // Redirect to index page
                        });
                    } else {
                        // ... (same as your existing error handling code)
                    }
                },
                error: function(xhr, status, error) {
                    // ... (same as your existing error handling code)
                },
                complete: function() {
                    btn.attr('disabled', false);
                    btn.html("Create Company");
                }
            });
        });
    });
</script>
@endsection
