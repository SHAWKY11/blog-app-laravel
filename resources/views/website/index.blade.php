@extends('layouts_web.master')
@section('title')
    @if (isset($category->name))
        {{ $category->name }}
    @elseif(isset($tag->name))
        {{ $tag->name }}
    @elseif(isset($query))
        Search for {{ $query }}
    @else
        Home
    @endif
@endsection

@section('css')
@endsection
@section('banner')
    @if (isset($category->name))
        {{ $category->name }}
    @elseif(isset($tag->name))
        {{ $tag->name }}
    @elseif(isset($query))
        Search for {{ $query }}
    @else
        Your partner in network optimization
    @endif
@endsection

@section('content')
    @if ($posts->isEmpty())
        <div class="content p-5 d-flex gap-5">
            <div class="left">
                <p>No posts found.</p>
            </div>
        </div>
    @else
        <div class="content p-5 d-flex gap-5">
            <div class="left">
                @foreach ($posts as $post)
                    <div class="card rounded-0 border-0 gap-1 mb-3">
                        <a href="{{ route('show.post', $post->id) }}" >
                            <img src="{{ $post->image_path }}" alt="" style="width: 700px;"/>
                            <h2 class="my-3">
                                {{ $post->title }}
                            </h2>
                        </a>

                        <div class="d-flex mb-3 post-meta">
                            <span class="post-date px-3">Author : <a href="{{route('show.author.post',$post->author)}}">{{ $post->author }}</a></span>
                            <span class="post-date border border-top-0 border-bottom-0 px-3">Category : <a
                                    href="{{ route('show-category', $post->category->id) }}">{{ $post->category->name }}</a></span>
                            <span
                                class="post-date border border-top-0 border-bottom-0 px-3">{{ $post->created_at->format('M j, Y') }}</span>
                            <span class="d-flex align-items-center px-3">
                                <i class="nav-icon fas fa-comment"
                                    style="margin-right: 5px;"></i>{{ $post->comments_count }}
                            </span>
                            <span class="d-flex align-items-center px-3">
                                <i class="nav-icon fas fa-eye" style="margin-right: 5px;"></i>{{ $post->views }}
                            </span>

                        </div>

                        <p class="text-muted fw-bolder mb-4">
                            {{ \Illuminate\Support\Str::limit(strip_tags($post->post_content), 200) }}
                        </p>



                        <a href="{{ route('show.post', $post->id) }}" class="text-muted fw-bold">Reat More > </a>
                    </div>
                @endforeach
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
    @endif
@endsection

@section('scripts')
@endsection
