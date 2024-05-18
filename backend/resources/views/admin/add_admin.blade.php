@extends('layout.app')
@section('title', 'Admins')
@section('admin-active', 'active')
@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <div class="mb-0">{{ session('success') }}</div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<section class="content-header">
    <div class="card col-md-8 mx-auto">
        <div class="card-header">
            <h4 class="md:hidden d-inline">Add Admins</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                @csrf
                <input type="text" placeholder="Name" name="name"
                class="form-control mb-0 @error('name') is-invalid @enderror"
                value="{{ old('name') }}">
                @error('name')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror

                <input type="email" placeholder="Email" name="email"
                class="form-control mt-2 @error('email') is-invalid @enderror"
                value="{{ old('email') }}">
                @error('email')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror

                <input type="password" placeholder="Password" name="password1" class="form-control mt-2 @error('password1') is-invalid @enderror">
                @error('password1')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror

                <input type="password" placeholder="Confirm Password" name="password2" class="form-control mt-2 @error('password2') is-invalid @enderror">
                @error('password2')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror

                <button class="btn btn-primary float-right mt-2">Create</button>
            </form>
        </div>
    </div>
</section>
@endsection
