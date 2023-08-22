@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Produk</h1>
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
                <form id="editProdukForm" method="POST" action="{{ route('produk.update', $produk->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="kode">Kode:</label>
                        <input type="text" name="kode" id="kode" class="form-control" value="{{ $produk->kode }}" required>
                        <span id="kode_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="ledger">Ledger:</label>
                        <select name="ledger" id="ledger" class="form-control" required>
                            <option value="">-- Select Ledger --</option>
                            @foreach($ledgers as $ledger)
                            <option value="{{ $ledger->kode }}" @if($produk->ledger == $ledger->kode) selected @endif>{{ $ledger->kode }} - {{ $ledger->name }}</option>
                            @endforeach
                        </select>
                        <span id="ledger_error" class="text-danger"></span>
                        @error('ledger'){{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" required>{{ $produk->keterangan }}</textarea>
                        <span id="keterangan_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="editProdukBtn" class="btn btn-primary">Update Produk</button>
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
    $(document).ready(function() {
        $("#editProdukForm").on('submit', function(e) {
            e.preventDefault();
            var btn = $('#editProdukBtn');
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
                            title: 'Produk berhasil diperbarui',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "{{ route('produk.index') }}";
                        });
                    } else {
                        btn.attr('disabled', false);
                        btn.html("Update Produk");
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
                    btn.html("Update Produk");
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
        });

        // Clear error messages on input
        $('#kode, #ledger, #keterangan').on('input', function() {
            $('#kode_error, #ledger_error, #keterangan_error').text('');
        });
    });
</script>
@endsection
