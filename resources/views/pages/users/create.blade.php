@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
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
                        <form id="create-user">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" placeholder="Masukkan Nama">
                                    <small class="text-danger" id="name_error"></small>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Masukkan Email">
                                    <small class="text-danger" id="email_error"></small>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password"
                                        placeholder="Masukkan Password">
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
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" id="btn-create" class="btn btn-primary">Simpan</button>
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
    <script type="application/javascript">
    $("#create-user").on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btn-create');
            btn.attr('disabled', true);
            btn.val("Loading...");
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
                    $(".preloader").fadeOut();
                    if (response.success) {
                        window.location.href = "{{ route('users.index') }}";
                        sessionStorage.setItem('success', response.message);
                    }
                },
                error: function(response) {
                    btn.attr('disabled', false);
                    btn.val("Simpan");
                    $('#name_error').text(response.responseJSON.errors.name);
                    $('#email_error').text(response.responseJSON.errors.email);
                    $('#password_error').text(response.responseJSON.errors.password);
                    $('#role_error').text(response.responseJSON.errors.role);
                }
            });

        });
    </script>
@endsection
