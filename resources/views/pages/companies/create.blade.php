@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Company</h1>
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
                <form id="createCompanyForm" method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" class="form-control" required>
                        <span class="text-danger" id="nama_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea name="alamat" class="form-control" required></textarea>
                        <span class="text-danger" id="alamat_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" required>
                        <span class="text-danger" id="email_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon:</label>
                        <input type="text" name="telepon" class="form-control" required>
                        <span class="text-danger" id="telepon_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo:</label>
                        <input type="file" name="logo" class="form-control-file" required>
                        <span class="text-danger" id="logo_error"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createCompanyBtn" class="btn btn-primary">Create Company</button>
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
    $("#createCompanyForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#createCompanyBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        let formData = new FormData(this);
        $('#nama_error').text('');
        $('#alamat_error').text('');
        $('#email_error').text('');
        $('#telepon_error').text('');
        $('#logo_error').text('');

        $.ajax({
            url: "{{ route('companies.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                $(".preloader").fadeOut();
                if (response.success) {
                    sessionStorage.setItem('success', response.message);
                    Swal.fire({
                        icon: 'success',
                        title: 'Company Created',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "{{ route('companies.index') }}";
                    });
                }
            },
            error: function(response) {
                btn.attr('disabled', false);
                btn.val("Simpan");

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while creating the company.',
                    confirmButtonText: 'OK'
                });

                $('#nama_error').text(response.responseJSON.errors.nama);
                $('#alamat_error').text(response.responseJSON.errors.alamat);
                $('#email_error').text(response.responseJSON.errors.email);
                $('#telepon_error').text(response.responseJSON.errors.telepon);
                $('#logo_error').text(response.responseJSON.errors.logo);
            }
        });
    });
</script>
@endsection