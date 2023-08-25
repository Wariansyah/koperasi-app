@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Simpanan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Daftar Simpanan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-simpanan" style="width: 100%" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Rekening Simpanan</th>
                                            <th>No. Induk</th>
                                            <th>Tanggal Buka</th>
                                            <th>Tanggal Tutup</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table-simpanan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('anggota.index') }}", // Ganti sesuai rute yang Anda gunakan untuk index simpanan
                    type: 'GET',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'rekening_simpanan', name: 'rekening_simpanan' },
                    { data: 'no_induk', name: 'no_induk' },
                    { data: 'tgl_buka', name: 'tgl_buka' },
                    { data: 'tgl_tutup', name: 'tgl_tutup' },
                    { data: 'nominal', name: 'nominal' },
                    { data: 'keterangan', name: 'keterangan' },
                ],
                // Rest of the DataTables settings
            });
        });
    </script>
@endsection
