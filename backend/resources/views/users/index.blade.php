@extends('layout.app')
@section('title', 'User Info')
@section('users-acitve', 'active')
@section('content')
    {{-- <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users Info</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users Info</li>
                    </ol>
                </div>
            </div>
        </div>
    </section> --}}

    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="md:hidden d-inline">Users</h4>
                            <div class="float-right d-inline">
                                <form action="">
                                    <div class="input-group float-right">
                                        <input type="text" class="form-control" value="{{ request('search_users') }}" name="search_users" placeholder="Search users..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Join on</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <a href="/dashboard">
                                        <tr style="cursor: pointer;">
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td><a href="{{ url('users/'.$user->id) }}" class="bg-primary btn btn-sm"><i class="fa-solid fa-circle-info"></i></a></td>
                                        </tr>
                                    </a>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="float-right">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
