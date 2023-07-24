@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit Role</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('roles.update', $role->id) }}" method="POST" id="update-role-form">
            @method('PATCH')
            @csrf
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" value="{{ $role->name }}" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br/>
                        <div class="container">
                            @foreach($permission as $value)
                                <input class="form-check-input" value="{{ $value->id }}" name="permission[]" type="checkbox" id="flexSwitchCheckDefault" @if(in_array($value->id, $rolePermissions)) checked="checked" @endif />
                                <label class="form-check-label" for="flexSwitchCheckDefault">{{ $value->name }}</label>
                                <br/>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="button" class="btn btn-primary" onclick="updateRole({{ $role->id }})">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function updateRole(roleId) {
            let formData = new FormData(document.getElementById('update-role-form'));
            $.ajax({
                url: "{{ route('roles.update', ':roleId') }}".replace(':roleId', roleId),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.code === 200) {
                        alert('Role berhasil diperbarui');
                        window.location.href = "{{ route('roles.index') }}";
                    } else {
                        alert('Role gagal diperbarui');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }
    </script>
@endsection
