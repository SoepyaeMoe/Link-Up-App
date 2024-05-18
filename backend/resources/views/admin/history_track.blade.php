@extends('layout.app')
@section('title', 'Admim History')
@section('admin-active', 'active')
@section('content')
<section class="content-header">
    <div class="card">
        {{-- <div class="card-header">
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
                <button class="btn btn-sm btn-primary float-right"><i class="fa-solid fa-plus mr-1"></i>Add Admin</button>
            </div>
        </div> --}}
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Content</th>
                        <th>Owner</th>
                        <th>Action on</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                        <tr>
                            <td>{{ $history->id }}</td>
                            <td>{{ $history->userInf->name }}</td>
                            <td>{{ $history->content }}</td>
                            <td><a href="{{ url('users/'.$history->owner) }}">{{ $history->ownerInf->name }}</a></td>
                            <td>{{ $history->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="float-right mt-3">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
