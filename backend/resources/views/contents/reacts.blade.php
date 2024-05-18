@extends('layout.app')
@section('title', 'Contents')
@section('contents-acitve', 'active')
@section('content')
    <section class="content-header">
        <div class="card">
            <div class="card-header">
                <div class="mb-2 mb-md-0">
                    <p class="mb-0">Created by: &nbsp; <a href="{{ url('users/'.$content->user->id) }}">{{ $content->user->name }}</a>.</p>
                    <p class="mb-0">Created on: &nbsp; {{ $content->created_at }}.</p>
                    <p class="mb-0">Content title: &nbsp; <b>{{ $content->title }}</b>.</p>
                    <p class="mb-0">{{ Str::limit($content->content, 100)}} <a href="{{ url('contents/'.$content->id) }}">more</a></p>
                </div>
                <div class="float-md-right">
                    <a href="{{ url('reacts/'.$content->id) }}" class="btn btn-primary position-relative">
                        Reacts
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          {{ count($content->reacts) }}
                        </span>
                    </a>

                    <a href="{{ url('comments/'.$content->id) }}" class="btn border position-relative">
                        Comments
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          {{ count($content->comments) }}
                        </span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(count($reacts) != 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Users</th>
                                <th>Created at</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reacts as $react)
                                <tr>
                                    <input type="hidden" value="{{ $react->id }}" id="react_id">
                                    <td>{{ $react->id }}</td>
                                    <td><a href="{{ url('users/'.$react->user->id) }}">{{ $react->user->name }}</a></td>
                                    <td>{{ $react->created_at }}</td>
                                    <td>
                                        <button class="bg-danger btn btn-sm" id="deleteReact"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h4 class="mx-auto">No react yet!</h4>
                    @endif
                </div>
                <div class="float-right">
                    {{ $reacts->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('extra-script')
<script>
    $(document).ready(function(){
        $('#deleteReact').click(function(){
            $react_id = $('#react_id').val();
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger m-2"
                },
                buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You want to remove react from this content.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Remove",
                cancelButtonText: "Cancel",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'get',
                    data: {'id': $react_id},
                    url: "{{ url('delete-react') }}",
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
