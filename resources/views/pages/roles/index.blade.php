@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Roles</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Roles</li>
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
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-role" style="width: 100%" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Permission</th>
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
            });

            sessionStorage.clear();
        }

        $('#table-role').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('roles.index') }}",
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'align-middle'
                },
                {
                    data: 'name',
                    className: 'align-middle'
                },
                {
                    data: 'permissions',
                    className: 'align-middle'
                },
                {
                    data: 'action',
                    className: 'align-middle text-center'
                }
            ],
            // Rest of the DataTables settings
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
            text: "Do you want to delete this reminder?",
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
                        url: "roles/" + id,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE',
                        },
                        success: function(data) {
                            if (data.success) {
                                toastr.success('success', data.message);
                                // Remove the row from the table
                                $(e).closest('tr').remove();
                            } else {
                                toastr.error('error', data.message);
                            }
                        }
                    });
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
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