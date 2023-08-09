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
                    <div class="card-header">
                        <a href="{{ route('kas.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-kas" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>User ID</th>
                                        <th>Kas Awal</th>
                                        <th>Kas Masuk</th>
                                        <th>Kas Keluar</th>
                                        <th>Kas Akhir</th>
                                        <th>Aksi</th>
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
                    render: function(data, type, row) {
                        return formatRupiah(data); // Memanggil fungsi formatRupiah
                    }
                },
                {
                    data: 'kas_masuk',
                    name: 'kas_masuk',
                    render: function(data, type, row) {
                        return formatRupiah(data); // Memanggil fungsi formatRupiah
                    }
                },
                {
                    data: 'kas_keluar',
                    name: 'kas_keluar',
                    render: function(data, type, row) {
                        return formatRupiah(data); // Memanggil fungsi formatRupiah
                    }
                },
                {
                    data: 'kas_akhir',
                    name: 'kas_akhir',
                    render: function(data, type, row) {
                        return formatRupiah(data); // Memanggil fungsi formatRupiah
                    }
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
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