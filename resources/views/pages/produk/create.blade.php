@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create New Produk</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Produk</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="createProdukForm" method="POST" action="{{ route('produk.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="kode">Kode:</label>
                        <input type="text" name="kode" id="kode" class="form-control" required>
                        <span id="kode_error" class="text-danger"></span>
                        @error('kode'){{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <label for="ledger">Ledger:</label>
                        <input type="text" name="ledger" id="ledger" class="form-control" required>
                        <span id="ledger_error" class="text-danger"></span>
                        @error('ledger'){{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" required></textarea>
                        <span id="keterangan_error" class="text-danger"></span>
                        @error('keterangan'){{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" id="createProdukBtn" class="btn btn-primary">Create Produk</button>
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="application/javascript">
    $("#createProdukForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#createProdukBtn');
        btn.attr('disabled', true);
        btn.html("Loading...");
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('#kode_error').text('');
        $('#ledger_error').text('');
        $('#keterangan_error').text('');

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Produk berhasil dibuat',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "{{ route('produk.index') }}";
                    });
                } else {
                    btn.attr('disabled', false);
                    btn.html("Create Produk");
                    if (response.errors) {
                        if (response.errors.kode) {
                            $('#kode_error').text(response.errors.kode[0]);
                        }
                        if (response.errors.ledger) {
                            $('#ledger_error').text(response.errors.ledger[0]);
                        }
                        if (response.errors.keterangan) {
                            $('#keterangan_error').text(response.errors.keterangan[0]);
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                btn.attr('disabled', false);
                btn.html("Create Produk");
                if (xhr.status === 422) {
                    var errors = JSON.parse(xhr.responseText).errors;
                    if (errors.kode) {
                        $('#kode_error').text(errors.kode[0]);
                    }
                    if (errors.ledger) {
                        $('#ledger_error').text(errors.ledger[0]);
                    }
                    if (errors.keterangan) {
                        $('#keterangan_error').text(errors.keterangan[0]);
                    }
                }
            }

        });

        $('#kode').on('input', function() {
            $('#kode_error').text('');
        });

        $('#ledger').on('input', function() {
            $('#ledger_error').text('');
        });

        $('#keterangan').on('input', function() {
            $('#keterangan_error').text('');
        });
    });
</script>
@endsection