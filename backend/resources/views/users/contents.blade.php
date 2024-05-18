@extends('users.user_detail')
@section('contents-active', 'bg-primary')
@section('contents')
<div class="col-12 mt-3">
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
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
                    <td>{{ $content->title }}</td>
                    <td>{{ Str::limit($content->content, 25, '...') }}</td>
                    <td>{{ count($content->reacts) }} </td>
                    <td>{{ count($content->comments) }} </td>
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
@endsection
