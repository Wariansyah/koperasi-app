@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <!-- ... -->
</div>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-user" style="width: 100%" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>No Induk</th>
                                        <th>Alamat</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
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
    $(document).ready(function() {
        if (sessionStorage.getItem('success')) {
            let data = sessionStorage.getItem('success');
            toastr.success('', data, {
                timeOut: 1500,
                preventDuplicates: true,
                progressBar: true,
                positionClass: 'toast-top-right',
            });

            sessionStorage.clear();
        }

        $('#table-user').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'align-middle'
                },
                {
                    data: 'name',
                    name: 'name',
                    className: 'align-middle'
                },
                {
                    data: 'email',
                    name: 'email',
                    className: 'align-middle'
                },
                {
                    data: 'role', // Ubah 'role' menjadi 'role'
                    name: 'role', // Ubah 'role' menjadi 'role'
                    className: 'align-middle'
                },
                {
                    data: 'no_induk',
                    name: 'no_induk',
                    className: 'align-middle'
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                    className: 'align-middle'
                },
                {
                    data: 'jenkel',
                    name: 'jenkel',
                    className: 'align-middle'
                },
                {
                    data: 'tmpt_lahir',
                    name: 'tmpt_lahir',
                    className: 'align-middle'
                },
                {
                    data: 'tgl_lahir',
                    name: 'tgl_lahir',
                    className: 'align-middle'
                },
                {
                    data: 'telepon',
                    name: 'telepon',
                    className: 'align-middle'
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'align-middle text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'align-middle text-center'
                }
            ],
        });
    });
</script>
@endsection