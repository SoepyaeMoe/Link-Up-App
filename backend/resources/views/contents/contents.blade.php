@extends('layout.app')
@section('title', 'Contents')
@section('contents-acitve', 'active')
@section('content')
    <section class="content-header">
        <div class="card">
            <div class="card-header">
                <h4 class="md:hidden d-inline">Contents</h4>
                <div class="float-right d-inline">
                    <form action="">
                        <div class="input-group mb-3 float-right">
                            <input type="text" class="form-control" value="{{ request('search_content') }}" name="search_content" placeholder="Search content..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Creator</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Reacts</th>
                            <th>Comments</th>
                            <th>Created at</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contents as $content)
                            <tr>
                                <td>{{ $content->id }}</td>
                                <td>{{ $content->user->name }}</td>
                                <td>{{ $content->title }}</td>
                                <td>{{ Str::limit($content->content, 20, '...') }}</td>
                                <td>{{ $content->react ? $content->react : '0' }}</td>
                                <td>{{ $content->comments ? count($content->comments) : '0' }}</td>
                                <td>{{ $content->created_at }}</td>
                                <td>
                                    <a href="{{ url('contents/'.$content->id) }}" class="bg-primary btn btn-sm"><i class="fa-solid fa-circle-info"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="float-right">
                    {{ $contents->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
