@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <!-- Your header content here -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Tambah User</h3>
                    </div>
                    <form id="create-user" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama">
                                <small class="text-danger" id="name_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan Email">
                                <small class="text-danger" id="email_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password">
                                <small class="text-danger" id="password_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control select2" data-toggle="select" id="role">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="role_error"></small>
                            </div>
                            <!-- Additional Fields -->
                            <div class="form-group">
                                <label for="no_induk">Nomor Induk</label>
                                <input type="text" class="form-control" name="no_induk" id="no_induk" placeholder="Masukkan Nomor Induk">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Masukkan Alamat"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="jenkel">Jenis Kelamin</label>
                                <select name="jenkel" class="form-control" id="jenkel">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tmpt_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tmpt_lahir" id="tmpt_lahir" placeholder="Masukkan Tempat Lahir">
                            </div>
                            <div class="form-group">
                                <label for="tgl_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir">
                            </div>
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input type="text" class="form-control" name="telepon" id="telepon" placeholder="Masukkan Nomor Telepon">
                            </div>
                            <!-- End of Additional Fields -->
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" id="btn-create" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#create-user").on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btn-create');
            btn.attr('disabled', true);
            btn.html("Loading...");
            let formData = new FormData(this);
            $('#name_error').text('');
            $('#email_error').text('');
            $('#password_error').text('');
            $('#role_error').text('');

            $.ajax({
                url: "{{ route('users.store') }}",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.attr('disabled', false);
                    btn.html("Simpan");
                    if (response.success) {
                        // Redirect to the index page on success
                        window.location.href = "{{ route('users.index') }}";
                    } else {
                        // Handle any potential server-side validation errors
                        if (response.errors) {
                            if (response.errors.name) {
                                $('#name_error').text(response.errors.name[0]);
                            }
                            if (response.errors.email) {
                                $('#email_error').text(response.errors.email[0]);
                            }
                            if (response.errors.password) {
                                $('#password_error').text(response.errors.password[0]);
                            }
                            if (response.errors.role) {
                                $('#role_error').text(response.errors.role[0]);
                            }
                        }
                    }
                },
                error: function() {
                    btn.attr('disabled', false);
                    btn.html("Simpan");
                    // Handle Ajax error if needed
                }
            });
        });
    });
</script>
@endsection