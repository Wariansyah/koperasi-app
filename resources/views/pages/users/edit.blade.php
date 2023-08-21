@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="content">
    <!-- ... -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="editUserForm" method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_induk">No. Induk:</label>
                        <input type="text" name="no_induk" id="no_induk" class="form-control" value="{{ $user->no_induk }}" required>
                        <span id="no_induk_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" value="{{ $user->alamat }}" required>
                        <span id="alamat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        <span id="email_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon:</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" value="{{ $user->telepon }}" required>
                        <span id="telepon_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="0" @if($user->status == 0) selected @endif>Belum Aktivasi</option>
                            <option value="1" @if($user->status == 1) selected @endif>Aktif</option>
                            <option value="2" @if($user->status == 2) selected @endif>Tidak Aktif</option>
                            <option value="3" @if($user->status == 3) selected @endif>Blokir</option>
                        </select>
                        <span id="status_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="jenkel">Jenis Kelamin:</label>
                        <select name="jenkel" id="jenkel" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ $user->jenkel === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $user->jenkel === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <span id="jenkel_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir:</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" value="{{ $user->tgl_lahir }}" required>
                        <span id="tgl_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tmpt_lahir">Tempat Lahir:</label>
                        <input type="text" name="tmpt_lahir" id="tmpt_lahir" class="form-control" value="{{ $user->tmpt_lahir }}" required>
                        <span id="tmpt_lahir_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="role">Peran:</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">-- Pilih Peran --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </select>
                        <span id="role_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="limit_pinjaman">Limit Pinjaman:</label>
                        <input type="number" name="limit_pinjaman" id="limit_pinjaman" class="form-control" value="{{ $user->limit_pinjaman }}" required>
                        <span id="limit_pinjaman_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="editUserBtn" class="btn btn-primary">Update User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
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
    $("#editUserForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#editUserBtn');
        btn.attr('disabled', true);
        btn.val("Loading...");
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('#name_error').text('');
        $('#no_induk_error').text('');
        $('#alamat_error').text('');
        $('#email_error').text('');
        $('#telepon_error').text('');
        $('#password_error').text('');
        $('#status_error').text('');
        $('#jenkel_error').text('');
        $('#tgl_lahir_error').text('');
        $('#tmpt_lahir_error').text('');
        $('#role_error').text('');
        $('#limit_pinjaman_error').text('');

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
                        title: 'User Update',
                        text: 'User Berhasi Diupdate.',
                        showConfirmButton: false,
                        timer: 1500 // Auto close after 1.5 seconds
                    }).then(function() {
                        window.location.href = "{{ route('users.index') }}";
                    });
                } else {
                    if (response.errors) {
                        // Handle error fields
                        // ...
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'An error occurred while updating the user.',
                        confirmButtonText: 'OK'
                    });
                }

                btn.attr('disabled', false);
                btn.val("Update User");
            },
            error: function(xhr, status, error) {
                // Handle error cases
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'An error occurred while updating the user.',
                    confirmButtonText: 'OK'
                });

                btn.attr('disabled', false);
                btn.val("Update User");
            }
        });
    });
</script>
@endsection