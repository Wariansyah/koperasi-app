<!-- resources/views/kas/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Kas Data Details
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ $data->username }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kas_awal">Kas Awal</label>
                        <input type="number" class="form-control" id="kas_awal" name="kas_awal" value="{{ $data->kas_awal }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kas_masuk">Kas Masuk</label>
                        <input type="number" class="form-control" id="kas_masuk" name="kas_masuk" value="{{ $data->kas_masuk }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kas_keluar">Kas Keluar</label>
                        <input type="number" class="form-control" id="kas_keluar" name="kas_keluar" value="{{ $data->kas_keluar }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kas_akhir">Kas Akhir</label>
                        <input type="number" class="form-control" id="kas_akhir" name="kas_akhir" value="{{ $data->kas_akhir }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $data->date }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea class="form-control" id="note" name="note" rows="3" readonly>{{ $data->note }}</textarea>
                    </div>
                    <a href="{{ route('kas.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
