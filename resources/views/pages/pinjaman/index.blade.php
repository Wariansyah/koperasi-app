@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Anggota</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Anggota</li>
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
                        <a href="{{ route('anggota.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-anggota" style="width: 100%" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Induk</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tgl Lahir</th>
                                        <th>Tempat Lahir</th>
                                        <th>Ibu Kandung</th>
                                        <th>Action</th>
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