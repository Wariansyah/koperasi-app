@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Company</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Company</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="editCompanyForm" method="POST" action="{{ route('companies.update', $company->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" class="form-control" value="{{ $company->nama }}" required>
                        <span class="text-danger" id="nama_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea name="alamat" class="form-control" required>{{ $company->alamat }}</textarea>
                        <span class="text-danger" id="alamat_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ $company->email }}" required>
                        <span class="text-danger" id="email_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon:</label>
                        <input type="text" name="telepon" class="form-control" value="{{ $company->telepon }}" required>
                        <span class="text-danger" id="telepon_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo:</label>
                        <input type="file" name="logo" class="form-control-file">
                        <span class="text-danger" id="logo_error"></span>
                        @if($company->logo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" style="max-width: 200px;">
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" id="editCompanyBtn" class="btn btn-primary">Update Company</button>
                        <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="application/javascript">
    $("#editCompanyForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#editCompanyBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
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
                        title: 'Company Updated',
                        text: 'Company Berhasil Diupdate.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "{{ route('companies.index') }}";
                    });
                } else {
                    if (response.errors) {
                        // Handle error fields
                        // ...
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'An error occurred while updating the company.',
                        confirmButtonText: 'OK'
                    });
                }

                btn.attr('disabled', false);
                btn.val("Update Company");
            },
            error: function(xhr, status, error) {
                // Handle error cases
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'An error occurred while updating the company.',
                    confirmButtonText: 'OK'
                });

                btn.attr('disabled', false);
                btn.val("Update Company");
            }
        });
    });
</script>
@endsection