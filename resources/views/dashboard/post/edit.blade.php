@extends('layouts.master')
@section('title')
    Dashboard
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@section('pagetitle1')
    Dashboard
@endsection

@section('pagetitle2')
    Posts
@endsection

@section('pagetitle3')
    Edit Post
@endsection

@section('content')
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Media</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="mediaForm" action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="image_desc">image Description : <span style="color: red;">*</span></label>
                            <input type="text" name="image_desc" class="form-control" id="image_desc">
                            <span id="image_desc-error" style="color: red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="image_file">image file : <span style="color: red;">*</span></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image_file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    <span id="image_file-error" style="color: red;"></span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="saveMediaBtn">Upload</button>
                    </form>

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <section class="content">

        <div class="container-fluid">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-default">
                <i class="nav-icon fas fa-plus">
                    add media
                </i>
            </button>
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title">Edit Post</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="post_name">Post Title: <span style="color: red;">*</span></label>
                                    <input type="input" name="post_name" class="form-control" id="post_name"
                                        placeholder="Enter Page Title" value="{{ $post->title }}">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Post Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="post_image" class="custom-file-input"
                                                id="exampleInputFile" value="{{ $post->image }}">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- select -->
                                <div class="form-group">
                                    <label>Page Category: <span style="color: red;">*</span></label>
                                    <select class="form-control" name="category">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if ($category->id == $post->category_id) selected @endif>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                 <div class="col-12">
                                    <div class="form-group">
                                        <label>Tags</label>
                                        <div class="select2-purple">
                                            <select class="select2" multiple="multiple" name="tags[]" id="tags" data-placeholder="Select a State"
                                                data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                @foreach ($tags as $tag)
                                                <option value="{{$tag->id}}" 
                                                    @if (in_array($tag->id, $post->tags->pluck('id')->toArray())) selected
                                                     @endif>
                                                    {{$tag->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                

                                <div class="form-group">
                                    <label>Post Content :</label>
                                    <textarea name="content" class="form-control ckeditor">{{ $post->post_content }} </textarea>
                                </div>


                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success swalDefaultSuccess">Update</button>
                            </div>
                        </form>
                    </div>

                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>  
<script>
    $(function() {
        $('.select2').select2({
            tags: true,  
            tokenSeparators: [','], 
            ajax: {
                url: '{{ route("tags.storeOrFetch") }}',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                        _token: '{{ csrf_token() }}' 
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(tag => ({
                            id: tag.id,
                            text: tag.name
                        }))
                    };
                },
                cache: true
            },
            createTag: function(params) {
                const term = $.trim(params.term);
                if (term === '') return null;
                return { id: term, text: term, newTag: true };
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            if (data.newTag) {
                $.ajax({
                    url: '{{ route("tags.storeOrFetch") }}',
                    type: 'POST',
                    data: {
                        name: data.text,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        data.id = response.id;
                        $('select[name="tags[]"]').append(new Option(data.text, data.id, false, true));
                    }
                });
            }
        });
    });
</script>
    <script>
  $(document).ready(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    });

    $('.swalDefaultSuccess').click(function(event) {
      event.preventDefault();
      // AJAX call here to handle form submission (if you're using AJAX for update).
      Toast.fire({
        icon: 'success',
        title: 'Post updated successfully.'
      });
    });
  });
</script>
@endsection
