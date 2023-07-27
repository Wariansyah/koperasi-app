<!-- resources/views/kas/edit.blade.php -->

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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Edit Kas Data
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('kas.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ $data->username }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kas_awal">Kas Awal</label>
                            <input type="number" class="form-control" id="kas_awal" name="kas_awal" value="{{ $data->kas_awal }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kas_masuk">Kas Masuk</label>
                            <input type="number" class="form-control" id="kas_masuk" name="kas_masuk" value="{{ $data->kas_masuk }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kas_keluar">Kas Keluar</label>
                            <input type="number" class="form-control" id="kas_keluar" name="kas_keluar" value="{{ $data->kas_keluar }}" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ $data->date }}" required>
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3">{{ $data->note }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('kas.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection