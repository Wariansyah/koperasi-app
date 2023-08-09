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
                                        <th>Id</th>
                                        <th>User ID</th>
                                        <th>Kas Awal</th>
                                        <th>Kas Masuk</th>
                                        <th>Kas Keluar</th>
                                        <th>Kas Akhir</th>
                                        <th>Date</th>
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
        ajax: {
            url: "{{ route('kas.index') }}",
        },
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
                render: $.fn.dataTable.render.number(',', '.', 0)
            },
            {
                data: 'kas_masuk',
                name: 'kas_masuk',
                render: $.fn.dataTable.render.number(',', '.', 0)
            },
            {
                data: 'kas_keluar',
                name: 'kas_keluar',
                render: $.fn.dataTable.render.number(',', '.', 0)
            },
            {
                data: 'kas_akhir',
                name: 'kas_akhir',
                render: $.fn.dataTable.render.number(',', '.', 0)
            },
            {
                data: 'date',
                name: 'date'
            },
        ]
    });
});
</script>
@endsection