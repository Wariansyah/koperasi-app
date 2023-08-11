@extends('layouts.app')

@section('content')
<div class="content-header">
    <!-- Content header code here -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-kas" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User ID</th>
                                        <th>Kas Awal</th>
                                        <th>Kas Masuk</th>
                                        <th>Kas Keluar</th>
                                        <th>Kas Akhir</th>
                                        <th>Tanggal</th>
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
    // Fungsi untuk mengformat angka menjadi format mata uang Rupiah
    function formatRupiah(angka) {
        var number_string = angka.toString();
        var split = number_string.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }
        $('#table-kas').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('kas.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'kas_awal',
                    name: 'kas_awal',
                },
                {
                    data: 'kas_masuk',
                    name: 'kas_masuk',
                },
                {
                    data: 'kas_keluar',
                    name: 'kas_keluar',
                },
                {
                    data: 'kas_akhir',
                    name: 'kas_akhir',
                },
                {
                    data: 'date',
                    name: 'date'
                },
            ]
        });

    function deleteItem(button) {
        var id = $(button).data('id');

        if (confirm('Are you sure you want to delete this data?')) {
            $.ajax({
                url: '/kas/' + id,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remove the deleted row from the table
                    $(button).closest('tr').remove();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    }
</script>
@endsection
