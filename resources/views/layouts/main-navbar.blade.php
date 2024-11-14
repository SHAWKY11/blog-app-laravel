 <nav class="main-header">
     <!-- Left navbar links -->
     <section class="content">
        <div class="container-fluid">
            <h2 class="text-center display-4"></h2>
            <div class="row">
                <div class="col-12">
                    <form  action="{{ route('posts.search') }}" method="GET">
                        <div class="input-group">

                            <input type="search" name="query" class="form-control form-control-lg" placeholder="Search on Posts">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
 </nav>
