@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Company</h1>
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
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-company" style="width: 100%" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Logo</th>
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
        $('#table-company').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('companies.index') }}",
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama',
                    className: 'align-middle'
                },
                {
                    data: 'alamat',
                    className: 'align-middle'
                },
                {
                    data: 'email',
                    className: 'align-middle'
                },
                {
                    data: 'telepon',
                    className: 'align-middle'
                },
                {
                    data: 'logo',
                    className: 'align-middle',
                    render: function(data, type, full, meta) {
                        return "<img src='" + data + "' height='150'/>";
                    }
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
        var name = $(button).data('nama');

        Swal.fire({
            title: 'Kamu Yakin?',
            text: 'Kamu ingin menghapus company ' + name + '.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/companies/' + id,
                    url: '/company/' + id,
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