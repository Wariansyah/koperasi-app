@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Ledger</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Ledger</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="editLedgerForm" method="POST" action="{{ route('ledgers.update', $ledger->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="kode">Kode:</label>
                        <input type="text" name="kode" id="kode" class="form-control" value="{{ $ledger->kode }}" required>
                        <span id="kode_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $ledger->name }}" required>
                        <span id="name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" required>{{ $ledger->keterangan }}</textarea>
                        <span id="keterangan_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="editLedgerBtn" class="btn btn-primary">Update Ledger</button>
                        <a href="{{ route('ledgers.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="application/javascript">
    function formatKode(kode) {
        var digits = kode.replace(/\D/g, '');
        var formatted = '';
        formatted += digits.slice(0, 1);
        for (var i = 1; i < digits.length && i < 7; i += 2) {
            formatted += '.' + digits.slice(i, i + 2);
        }
        return formatted;
    }

    const kodeInput = document.getElementById('kode');
    kodeInput.addEventListener('input', function() {
        this.value = formatKode(this.value);
    });

    $("#editLedgerForm").on('submit', function(e) {
        e.preventDefault();
        var btn = $('#editLedgerBtn');
        btn.attr('disabled', true);
        btn.html("Loading...");
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('#kode_error').text('');
        $('#name_error').text('');
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
                        title: 'Ledger berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "{{ route('ledgers.index') }}";
                    });
                } else {
                    btn.attr('disabled', false);
                    btn.html("Update Ledger");
                    if (response.errors) {
                        if (response.errors.kode) {
                            $('#kode_error').text(response.errors.kode[0]);
                        }
                        if (response.errors.name) {
                            $('#name_error').text(response.errors.name[0]);
                        }
                        if (response.errors.keterangan) {
                            $('#keterangan_error').text(response.errors.keterangan[0]);
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                btn.attr('disabled', false);
                btn.html("Update Ledger");
                if (xhr.status === 422) {
                    var errors = JSON.parse(xhr.responseText).errors;
                    if (errors.kode) {
                        $('#kode_error').text(errors.kode[0]);
                    }
                    if (errors.name) {
                        $('#name_error').text(errors.name[0]);
                    }
                    if (errors.keterangan) {
                        $('#keterangan_error').text(errors.keterangan[0]);
                    }
                }
            }
        });

        $('#kode, #name, #keterangan').on('input', function() {
            $('#kode_error, #name_error, #keterangan_error').text('');
        });
    });
</script>

@endsection
