@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <!-- ... (existing code) ... -->
</div>
<!-- /.content-header -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="kas-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Kas Awal</th>
                                <th>Kas Masuk</th>
                                <th>Kas Keluar</th>
                                <th>Kas Akhir</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Include the required Toastr plugin CSS and JavaScript files -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Include the required SweetAlert plugin JavaScript file -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include the DataTables library -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<!-- Include the FormatHelper.js script -->
<script src="{{ asset('Helpers/FormatHelper.js') }}"></script>

<script type="application/javascript">
    // Define the formatRupiah function in JavaScript
    function formatRupiah(nominal) {
        return "Rp. " + new Intl.NumberFormat("id-ID").format(nominal);
    }

    $(document).ready(function() {
        $('#kas-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('kas.index') }}", // Make sure this route matches your controller's route
            type: 'GET',
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'kas_awal',
                    name: 'kas_awal',
                    render: function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'kas_masuk',
                    name: 'kas_masuk',
                    render: function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'kas_keluar',
                    name: 'kas_keluar',
                    render: function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'kas_akhir',
                    name: 'kas_akhir',
                    render: function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'date',
                    name: 'date'
                },
            ]
        });
    });

    function deleteItem(e) {
        // ... (existing code) ...
    }
</script>
@endsection
