@extends('layouts.app')

@section('content')
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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('anggota.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-anggota" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Induk</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Tempat Lahir</th>
                                        <th>Ibu Kandung</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#table-anggota').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('anggota.index') }}"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex' 
                },
                {
                    data: 'no_induk', 
                    name: 'no_induk' 
                },
                { 
                    data: 'nama', 
                    name: 'nama' 
                },
                { 
                    data: 'alamat', 
                    name: 'alamat' 
                },
                { 
                    data: 'telepon', 
                    name: 'telepon' 
                },
                { 
                    data: 'jenkel', 
                    name: 'jenkel' 
                },
                { 
                    data: 'tgl_lahir', 
                    name: 'tgl_lahir' 
                },
                { 
                    data: 'tempat_lahir', 
                    name: 'tempat_lahir'
                },
                { 
                    data: 'ibu_kandung', 
                    name: 'ibu_kandung' 
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
    });
    
    function deleteItem(e) {
        let id = e.getAttribute('data-id');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: true
        });

        swalWithBootstrapButtons.fire({
            title: 'Apakah Anda yakin ingin melanjutkan?',
            text: "Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batalkan!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('anggota.destroy', '') }}" + "/" + id,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'DELETE',
                    },
                    success: function(data) {
                        if (data.success) {
                            swalWithBootstrapButtons.fire(
                                'Terhapus!',
                                'Anggota berhasil dihapus.',
                                "success"
                            ).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        swalWithBootstrapButtons.fire(
                            'Gagal',
                            'Gagal menghapus Anggota.',
                            'error'
                        );
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Dibatalkan',
                    'Anggota batal di hapus :)',
                    'error'
                );
            }
        });
    }
</script>
@endsection
