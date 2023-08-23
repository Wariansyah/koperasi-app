@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Anggota</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Anggota</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createAnggotaForm" method="POST" action="{{ route('anggota.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="no_induk">No Induk:</label>
                        <input type="text" name="no_induk" class="form-control" required>
                        <span class="text-danger" id="no_induk_error"></span>
                    </div>
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
                        <label for="telepon">Telepon:</label>
                        <input type="text" name="telepon" class="form-control" required>
                        <span class="text-danger" id="telepon_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="jenkel">Jenis Kelamin:</label>
                        <select name="jenkel" id="jenkel" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <span id="jenkel_error" class="text-danger"></span>
                        @error('jenkel'){{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <label for="tnggl_lahir">Tanggal Lahir:</label>
                        <input type="date" name="tnggl_lahir" class="form-control" required>
                        <span class="text-danger" id="tnggl_lahir_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="tmpt_lahir">Tempat Lahir:</label>
                        <input type="text" name="tmpt_lahir" class="form-control" required>
                        <span class="text-danger" id="tmpt_lahir_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="ibu_kandung">Ibu Kandung:</label>
                        <input type="text" name="ibu_kandung" class="form-control" required>
                        <span class="text-danger" id="ibu_kandung_error"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createAnggotaBtn" class="btn btn-primary">Create Anggota</button>
                        <a href="{{ route('anggota.index') }}" class="btn btn-secondary">Cancel</a>
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
    $("#createAnggotaForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#createAnggotaBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        let formData = new FormData(this);
        $('#no_induk_error').text('');
        $('#name_error').text('');
        $('#alamat_error').text('');
        $('#telepon_error').text('');
        $('#jenkel_error').text('');
        $('#tnggl_lahir_error').text('');
        $('#tmpt_lahir_error').text('');
        $('#ibu_kandung_error').text('');

        $.ajax({
            url: "{{ route('anggota.store') }}",
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
                        title: 'Anggota Created',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500 
                    }).then(function() {
                        window.location.href = "{{ route('anggota.index') }}";
                    });
                }
            },
            error: function(response) {
                btn.attr('disabled', false);
                btn.val("Simpan");

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while creating the user.',
                    confirmButtonText: 'OK'
                });

                $('#no_induk_error').text(response.responseJSON.errors.no_induk);
                $('#name_error').text(response.responseJSON.errors.name);
                $('#alamat_error').text(response.responseJSON.errors.alamat);
                $('#telepon_error').text(response.responseJSON.errors.telepon);
                $('#jenkel_error').text(response.responseJSON.errors.jenkel);
                $('#tnggl_lahir_error').text(response.responseJSON.errors.tnggl_lahir);
                $('#tmpt_lahir_error').text(response.responseJSON.errors.tmpt_lahir);
                $('#ibu_kandung_error').text(response.responseJSON.errors.ibu_kandung);
            }
        });
    });
</script>
@endsection
