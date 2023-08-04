@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Kas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Kas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
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
                                <th>Note</th>
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
        // ... Existing code ...

        $('#kas-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('kas.index') }}",
            type: 'GET',
            columns: [
                {
                    data: 'id', // Make sure the 'id' column name matches the column name in your database table
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
                {
                    data: 'note',
                    name: 'note'
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
            title: 'Are you sure?',
            text: "Do you want to delete this data?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST', // Change this to 'DELETE'
                    url: "kas/" + id,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'DELETE',
                    },
                    success: function(data) {
                        if (data.code === 200) {
                            toastr.success('Success', data.message);
                            // Remove the row from the table
                            $(e).closest('tr').remove();
                        } else {
                            toastr.error('Error', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error', 'Failed to delete Kas data');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Data deletion canceled',
                    'error'
                );
            }
        });
    }
</script>

@endsection
