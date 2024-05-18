@extends('layout.app')
@section('title', 'Admins')
@section('admin-active', 'active')
@section('content')
<section class="content-header">
    <div class="card">
        <div class="card-header">
            <h4 class="md:hidden d-inline">Admins</h4>
            <div class="float-right d-inline">
                <form action="">
                    <div class="input-group mb-3 float-right">
                        <input type="text" class="form-control" value="{{ request('search_admins') }}" name="search_admins" placeholder="Search admins..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
                <a href="{{ url('add-admin') }}" class="btn btn-sm btn-primary float-right"><i class="fa-solid fa-plus mr-1"></i>Add Admin</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admin_users as $user)
                        <tr style="cursor: pointer;">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td><a href="{{ url('admin-history/'.$user->id) }}" class="bg-primary btn btn-sm"><i class="fa-solid fa-clock-rotate-left"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
