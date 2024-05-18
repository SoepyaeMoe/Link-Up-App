@extends('layout.app')
@section('title', 'Contents')
@section('contents-acitve', 'active')
@section('content')
    <section class="content-header">
        <div class="card">
            <div class="card-header">
                <div class="mb-2 mb-md-0 d-flex justify-content-between">
                    <div>
                        <p class="mb-0">Created by: &nbsp; <a href="{{ url('users/'.$content->user->id) }}">{{ $content->user->name }}</a>.</p>
                        <p class="mb-0">Created on: &nbsp; {{ $content->created_at }}.</p>
                        <p class="mb-0">Content title: &nbsp; <b>{{ $content->title }}</b>.</p>
                        <p class="mb-0">{{ Str::limit($content->content, 20)}} <a href="{{ url('contents/'.$content->id) }}">more</a></p>
                    </div>

                    <div>
                        <form action="">
                            <div class="input-group mb-3 float-right">
                                <input type="text" class="form-control" value="{{ request('search_comments') }}" name="search_comments" placeholder="Search comments..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="float-md-right">
                    <a href="{{ url('reacts/'.$content->id) }}" class="btn border position-relative">
                        Reacts
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          {{ count($content->reacts) }}
                        </span>
                    </a>

                    <a href="{{ url('comments/'.$content->id) }}" class="btn btn-primary position-relative">
                        Comments
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          {{ count($content->comments) }}
                        </span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(count($comments) != 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Users</th>
                                <th>Comments</th>
                                <th>Created at</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $comment)
                                <tr>
                                    <input type="hidden" value="{{ $comment->id }}" id="comment_id">
                                    <td>{{ $comment->id }}</td>
                                    <td><a href="{{ url('users/'.$comment->user->id) }}">{{ $comment->user->name }}</a></td>
                                    <td>{{ $comment->comment }}</td>
                                    <td>{{ $comment->created_at }}</td>
                                    <td>
                                        <button class="bg-danger btn btn-sm deleteComment"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h4 class="mx-auto">No comments here.</h4>
                    @endif
                </div>
                <div class="float-right">
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra-script')
<script>
    $(document).ready(function(){
        $('.deleteComment').click(function(){
            $comment_id = $('#comment_id').val();
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger m-2"
                },
                buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You want to delete comment from this content.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'get',
                    data: {'id': $comment_id},
                    url: "{{ url('delete-comment') }}",
                    success: response => {
                        if(response.status == 200){
                            swalWithBootstrapButtons.fire({
                            title: "Removed!",
                            icon: "success"
                            });
                            this.parentElement.parentElement.remove();
                        }else{
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                            });
                        }
                    }
                    });

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Your imaginary file is safe :)",
                    icon: "error"
                    });
                }
                });
        });
    });
</script>
@endsection
