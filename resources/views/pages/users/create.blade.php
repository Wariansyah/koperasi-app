@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">User</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="id">ID:</label>
                        <input type="text" name="id" id="id" class="form-control" required>
                        <span id="id_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_induk">No. Induk:</label>
                        <input type="text" name="no_induk" id="no_induk" class="form-control" required>
                        <span id="no_induk_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
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
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span id="password_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                            <option value="Blokir">Blokir</option>
                        </select>
                        <span id="status_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin:</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <span id="jenis_kelamin_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir:</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" required>
                        <span id="tgl_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir:</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required>
                        <span id="tempat_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="limit_pinjaman">Limit Pinjaman:</label>
                        <input type="number" name="limit_pinjaman" id="limit_pinjaman" class="form-control" required>
                        <span id="limit_pinjaman_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createUserBtn" class="btn btn-primary">Create User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="application/javascript">
    $(document).ready(function() {
        // Set default value for status
        $('#status').val('Aktif');

        // Set status to Aktif when selected
        $('#status').on('change', function() {
            if ($(this).val() === 'Aktif') {
                $(this).val('Aktif');
                // Update the display status
                $('#status').html('<span class="badge badge-success text-white">Aktif</span>');
                $('#status').html('<span class="badge badge-warning text-white">Tidak Aktif</span>');
                $('#status').html('<span class="badge badge-danger text-white">Blokir</span>');
            }
        });
    });

    $("#createUserForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#createUserBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        let formData = new FormData(this);
        $('#id_error').text('');
        $('#name_error').text('');
        $('#no_induk_error').text('');
        $('#alamat_error').text('');
        $('#email_error').text('');
        $('#telepon_error').text('');
        $('#password_error').text('');
        $('#status_error').text('');
        $('#jenis_kelamin_error').text('');
        $('#tgl_lahir_error').text('');
        $('#tempat_lahir_error').text('');
        $('#limit_pinjaman_error').text('');

        $.ajax({
            url: "{{ route('users.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                $(".preloader").fadeOut();
                if (response.success) {
                    window.location.href = "{{ route('users.index') }}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(response) {
                btn.attr('disabled', false);
                btn.val("Simpan");
                $('#id_error').text(response.responseJSON.errors.id);
                $('#name_error').text(response.responseJSON.errors.nama);
                $('#no_induk_error').text(response.responseJSON.errors.no_induk);
                $('#alamat_error').text(response.responseJSON.errors.alamat);
                $('#email_error').text(response.responseJSON.errors.email);
                $('#telepon_error').text(response.responseJSON.errors.telepon);
                $('#password_error').text(response.responseJSON.errors.password);
                $('#status_error').text(response.responseJSON.errors.status);
                $('#jenis_kelamin_error').text(response.responseJSON.errors.jenis_kelamin);
                $('#tgl_lahir_error').text(response.responseJSON.errors.tgl_lahir);
                $('#tempat_lahir_error').text(response.responseJSON.errors.tmpt_lahir);
                $('#limit_pinjaman_error').text(response.responseJSON.errors.limit_pinjaman);
            }
        });
    });
</script>
@endsection