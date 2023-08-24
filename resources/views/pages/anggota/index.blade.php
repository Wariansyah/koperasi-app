@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Anggota</h1>
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
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('anggota.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-anggota" style="width: 100%" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Rekening</th>
                                            <th>No.Induk</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>Telepon</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Tgl Lahir</th>
                                            <th>Tempat Lahir</th>
                                            <th>Ibu Kandung</th>
                                            <th>Action</th>
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
        $('#table-anggota').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('anggota.index') }}",
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'align_middle'
                },
                {
                    data: 'rekening',
                    className: 'align_middle'
                },
                {
                    data: 'no_induk',
                    className: 'align_middle'
                },
                {
                    data: 'nama',
                    className: 'align_middle'
                },
                {
                    data: 'alamat',
                    className: 'align_middle'
                },
                {
                    data: 'telepon',
                    className: 'align_middle'
                },
                {
                    data: 'jenkel',
                    className: 'align_middle'
                },
                {
                    data: 'tnggl_lahir',
                    className: 'align_middle'
                },
                {
                    data: 'tmpt_lahir',
                    className: 'align_middle'
                },
                {
                    data: 'ibu_kandung',
                    className: 'align_middle'
                },
                {
                    data: 'action',
                    className: 'align-middle text-center'
                }
            ],
            // Rest of the DataTables settings
        });
    });

    function deleteItem(button) {
        var id = $(button).data('id');
        var name = $(button).data('name');

        Swal.fire({
            title: 'Kamu Yakin?',
            text: 'Kamu ingin menghapus anggota ' + name + '.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/anggota/' + id,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Remove the deleted row from the table
                        $(button).closest('tr').remove();

                        Swal.fire(
                            'Deleted!',
                            name + ' Telah dihapus',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    }


</script>
@endsection
