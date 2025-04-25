{{-- resources/views/admin/settings.blade.php --}}
@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1><i class="fas fa-cogs"></i> Settings</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="#site" data-toggle="tab">Site Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Change Password</a></li>
        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">
            <!-- Profile Settings -->
            <div class="tab-pane active" id="profile">
                <form method="POST" action="{{ route('admin.settings') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input value="{{ Auth::user()->name }}" type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input value="{{ Auth::user()->email }}" type="email" class="form-control" name="email" id="email">
                    </div>
                    <button class="btn btn-primary" type="submit">Save Profile</button>
                </form>
            </div>

            <!-- Site Settings -->
            <div class="tab-pane" id="site">
                <form method="POST" action="{{ route('admin.settings') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="site_name">Site Name</label>
                        <input type="text" class="form-control" name="site_name" id="site_name" value="{{ config('app.name') }}">
                    </div>
                    <div class="form-group">
                        <label for="logo">Site Logo</label>
                        <input type="file" class="form-control-file" name="logo" id="logo">
                    </div>
                    <button class="btn btn-success" type="submit">Update Site</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="tab-pane" id="password">
                <form method="POST" action="{{ route('admin.settings') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" name="current_password" id="current_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" name="new_password" id="new_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation">
                    </div>
                    <button class="btn btn-warning" type="submit">Change Password</button>
                </form>
            </div>
        </div>
    </div><!-- /.card-body -->
</div>
@endsection
