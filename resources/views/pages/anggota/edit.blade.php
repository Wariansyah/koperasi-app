@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Anggota</h1>
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
                <form id="editAnggotaForm" method="POST" action="{{ route('anggota.update', $anggota->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="no_induk">No Induk:</label>
                        <input type="text" name="no_induk" class="form-control" value="{{ $anggota->no_induk }}" required>
                        <span class="text-danger" id="no_induk_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" class="form-control" value="{{ $anggota->nama }}" required>
                        <span class="text-danger" id="nama_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea name="alamat" class="form-control" required>{{ $anggota->alamat }}</textarea>
                        <span class="text-danger" id="alamat_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon:</label>
                        <input type="text" name="telepon" class="form-control" value="{{ $anggota->telepon }}" required>
                        <span class="text-danger" id="telepon_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="jenkel">Jenis Kelamin:</label>
                        <select name="jenkel" class="form-control" required>
                            <option value="Laki-laki" {{ $anggota->jenkel == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $anggota->jenkel == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <span class="text-danger" id="jenkel_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="tnggl_lahir">Tanggal Lahir:</label>
                        <input type="date" name="tnggl_lahir" class="form-control" value="{{ $anggota->tgl_lahir }}" required>
                        <span class="text-danger" id="tnggl_lahir_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="tmpt_lahir">Tempat Lahir:</label>
                        <input type="text" name="tmpt_lahir" class="form-control" value="{{ $anggota->tmpt_lahir }}" required>
                        <span class="text-danger" id="tmpt_lahir_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="ibu_kandung">Ibu Kandung:</label>
                        <input type="text" name="ibu_kandung" class="form-control" value="{{ $anggota->ibu_kandung }}" required>
                        <span class="text-danger" id="ibu_kandung_error"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="editAnggotaBtn" class="btn btn-primary">Update Anggota</button>
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
    $("#editAnggotaForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#editAnggotaBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('#no_induk_error').text('');
        $('#name_error').text('');
        $('#alamat_error').text('');
        $('#telepon_error').text('');
        $('#jenkel_error').text('');
        $('#tnggl_lahir_error').text('');
        $('#tmpt_lahir_error').text('');
        $('#ibu_kandung_error').text('');

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
                        title: 'Anggota Update',
                        text: 'Anggota Berhasi Diupdate.',
                        showConfirmButton: false,
                        timer: 1500 
                    }).then(function() {
                        window.location.href = "{{ route('anggota.index') }}";
                    });
                } else {
                    if (response.errors) {
                        // Handle error fields
                        // ...
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'An error occurred while updating the anggota.',
                        confirmButtonText: 'OK'
                    });
                }

                btn.attr('disabled', false);
                btn.val("Update Angota");
            },
            error: function(xhr, status, error) {
                // Handle error cases
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'An error occurred while updating the anggota.',
                    confirmButtonText: 'OK'
                });

                btn.attr('disabled', false);
                btn.val("Update Anggota");
            }
        });
    });
</script>
@endsection
