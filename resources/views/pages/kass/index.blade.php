@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">kass</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">kass</li>
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
                            <a href="{{ route('kass.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-user" style="width: 100%" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
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
        if (sessionStorage.getItem('success')) {
            let data = sessionStorage.getItem('success');
            toastr.success('', data, {
                timeOut: 1500,
                preventDuplicates: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                // Redirect
            });

            sessionStorage.clear();
        }

        $('#table-user').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('users.index') }}",
                type: 'GET'
            },
            "responsive": false,
            "language": {
                "oPaginate": {
                    "sNext": "<i class='fas fa-angle-right'>",
                    "sPrevious": "<i class='fas fa-angle-left'>",
                },
                processing: '<img src="{{ asset('img/loader/loader3.gif') }}">',
            },

            columns: [{
                    data: 'DT_RowIndex', className:'align-middle'
                },
                {
                    data: 'name', className:'align-middle'
                },
                {
                    data: 'email', className:'align-middle'
                },
                {
                    data: 'role', className:'align-middle'
                },
                {
                    data: 'status', className:'align-middle text-center'
                },
                {
                    data: 'action', className:'align-middle text-center'
                }
            ],
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
            text: "Do you want to delete this reminder ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "users/" + id,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE',
                        },
                        success: function(data) {
                            if (data.success) {
                                toastr.success('success',data.message);
                                var oTable = $('#table-user').dataTable();
                                oTable.fnDraw(false);
                            }
                            else{
                                toastr.error('error',data.message);
                            }
                        }

                    });
                }
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    name + ' cancel deleted',
                    'error'
                );
            }
        });
    }
    </script>
@endsection
