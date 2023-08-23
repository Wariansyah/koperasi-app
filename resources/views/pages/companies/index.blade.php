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
                        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-company" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Logo</th>
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
@endsection

@section('scripts')
<script type="application/javascript">
    $(document).ready(function() {
        $('#table-company').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('companies.index') }}",
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'nama', name: 'nama'},
                { data: 'alamat', name: 'alamat' },
                { data: 'email', name: 'email' },
                { data: 'telepon', name: 'telepon' },
                {
                    data: 'logo',
                    className: 'logo',
                    render: function(data, type, full, meta) {
                        return '<img src="{{ Storage::url("/") }}' + data + '" height="50"/>';
                    }
                },
                {
                    data: 'action',
                    className: 'align-middle text-center'
                },
            ]
        });

        // Handling delete action using AJAX
        $('#table-company').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            if (confirm("Are you sure you want to delete this company?")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('companies.destroy', '') }}" + '/' + id,
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $('#table-company').DataTable().ajax.reload();
                        alert('Company deleted successfully.');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
</script>
@endsection
