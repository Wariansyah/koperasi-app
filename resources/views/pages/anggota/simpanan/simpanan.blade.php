@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tabel Simpanan</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-simpanan" style="width: 100%" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Rekening Simpanan</th>
                                        <th>No Induk</th>
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
<script type="application/javascript">
$(document).ready(function() {
    $('#table-simpanan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('anggota', $anggotaId) }}",
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', className: 'align_middle' },
            { data: 'rekening_simpanan', className: 'align_middle' },
            { data: 'no_induk', className: 'align_middle' },
            { data: 'tgl_buka', className: 'align_middle' },
            { data: 'tgl_tutup', className: 'align_middle' },
            { data: 'nominal', className: 'align_middle' },
            { data: 'keterangan', className: 'align_middle' },
        ],
        // Rest of the DataTables settings
    });
});
</script>
@endsection
