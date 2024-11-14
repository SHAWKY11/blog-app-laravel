@extends('layouts_web.master')
@section('title')
    {{$post->title}}
@endsection

@section('css')
    <style>
        .tags h4 {
            margin: 0;
            white-space: nowrap;
        }

        ul.comments .comment-block {
            position: relative;
            width: 100%;
            padding: 20px 20px 30px;
            border-radius: 5px;
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        div {
            display: block;
            unicode-bidi: isolate;
        }

        body {
            font-family: 'Roboto Slab', serif;
            font-size: 16px;
            font-weight: 300;
            line-height: 1.5;
            color: #3c434f;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -ms-text-size-adjust: 100%;
        }

        li {
            text-align: -webkit-match-parent;
        }

        ul.comments {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        ul {
            list-style-type: disc;
        }
    </style>
@endsection
@section('banner')
    {{ $post->title }}
@endsection

@section('content')
    <div class="content p-5 d-flex gap-5">
        <div class="left">
            <div class="card rounded-0 border-0 gap-1 mb-3">
                <img src="{{ $post->image_path }}" alt="" />
                <h2 class="my-3">
                    {{ $post->title }}
                </h2>

                <div class="d-flex mb-3 post-meta">
                    <span class="post-date px-3">Author : <a href="">{{ $post->author }}</a></span>
                    <span class="post-date border border-top-0 border-bottom-0 px-3">Category : <a
                            href="">{{ $post->category->name }}</a></span>
                    <span
                        class="post-date border border-top-0 border-bottom-0 px-3">{{ $post->created_at->format('M j, Y') }}</span>
                    <span class="d-flex align-items-center px-3">
                        <i class="nav-icon fas fa-comment" style="margin-right: 5px;"></i>{{ $post->comments_count }}
                    </span>
                    <span class="d-flex align-items-center px-3">
                        <i class="nav-icon fas fa-eye" style="margin-right: 5px;"></i>{{ $post->views }}
                    </span>

                </div>

                <p class="text-muted fw-bolder mb-4">
                    {{ strip_tags($post->post_content) }}
                </p>

            </div>
            <hr />
            <div class="d-flex align-items-center">
                <h3 class="mb-0">Tags:</h3>
                <div class="d-flex flex-wrap gap-3 ms-3">
                    <!-- Added margin start to create space between the heading and tags -->
                    @foreach ($tags as $tag)
                        <a href="{{ route('show-tag', $tag->id) }}">
                            <h4 class="bg-dark text-white rounded-5 p-2 fs-6">{{ $tag->name }}</h4>
                        </a>
                    @endforeach
                </div>
            </div>
            <hr />
            <h3 class="text-danger message mb-3">Comments:</h3>
            <div class="comment-block">
                <span class="comment-by">
                    @foreach ($comments as $comment)
                        <strong class="comment-author  px-3">{{ $comment->name }}</strong>
                        <span class="comment-date ">|</span>
                        <span class="comment-date  px-3">{{ $comment->created_at->format('F j, Y, g:i A') }}</span>
                        <p class="comment-date  px-3">{{ $comment->comment }}</p>
                    @endforeach
                </span>

            </div>
            <hr />
            <form id="commentForm">
    <h3 class="text-danger mb-3">LEAVE A COMMENT</h3>
    <div id="comment-message" class="mb-3"></div> <!-- Placeholder for success message -->
    <input type="hidden" name="post_id" value="{{ $post->id }}">
    <input class="form-control form-control-lg mb-3" name="comment" type="text" placeholder="Comment">
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Username" name="name">
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Enter email">
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-block btn-primary btn-lg" id="submit-comment">Post Comment</button>
</form>






        </div>
        <div class="right">
            <div class="search w-100">
                <div class="mb-5 position-relative">
                    <form action="{{ route('search.posts') }}" method="GET">
                        <input type="search" class="form-control w-100 pe-5" name="query" id="search"
                            placeholder="Search Posts" />
                        <i class="fa-solid fa-magnifying-glass position-absolute"
                            style="top: 50%; right: 10px; transform: translateY(-50%);"></i>
                    </form>
                </div>
            </div>

            <h3 class="text-danger fw-bolder">Categories</h3>
            <hr />

            <ul class="list-unstyled">
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('show-category', $category->id) }}"><i class="fa-solid fa-angle-right"></i>
                            {{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>

            <h3 class="text-danger fw-bolder mt-5">Tags</h3>
            <hr />

            <div class="tags gap-3">
                @foreach ($tags as $tag)
                    <a href="{{ route('show-tag', $tag->id) }}">
                        <h4 class="bg-dark text-white rounded-5 p-2 fs-6">{{ $tag->name }}</h4>
                    </a>
                @endforeach
            </div>
        </div>
        <hr />
    </div>

    {{-- {{ $posts->withQueryString()->links() }} --}}
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
     $('#submit-comment').on('click', function(e) {
    e.preventDefault();
    
    let formData = $('#commentForm').serialize();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '/comments',
        data: formData,
        dataType: 'json',
        success: function(response) {
            $('#comment-message').html('<h3>Thank You for Your Message!</h3><p>Your comment has been sent successfully. We appreciate your feedback!</p>')
                .css('color', 'green')
                .fadeIn();

            $('#commentForm').fadeOut(1000, function() {
                $('#commentForm')[0].reset();
                $('#commentForm').fadeIn();
            });
        },
        error: function(response) {
            $('#comment-message').html('<p>Failed to post your comment. Please try again.</p>')
                .css('color', 'red')
                .fadeIn();
        }
    });
});


    </script>
@endsection
