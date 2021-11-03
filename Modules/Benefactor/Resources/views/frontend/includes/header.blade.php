<header class="header-global">
    <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg headroom py-lg-3 px-lg-6 navbar-dark navbar-theme-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img class="navbar-brand-dark common" src="{{asset('img/backend-logo.jpg')}}" height="35" alt="Logo light">
                <img class="navbar-brand-light common" src="{{asset('img/backend-logo.jpg')}}" height="35" alt="Logo dark">
            </a>
            <div class="navbar-collapse collapse" id="navbar_global">
                <div class="navbar-collapse-header">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="/">
                                <img src="{{asset('img/backend-logo.jpg')}}" height="35" alt="Logo Impact">
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <a href="#navbar_global" role="button" class="fas fa-times" data-toggle="collapse"
                                data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false"
                                aria-label="Toggle navigation"></a>
                        </div>
                    </div>
                </div>
                <ul class="navbar-nav navbar-nav-hover justify-content-center">
                    <li class="nav-item">
                        <a href="{{ route('frontend.posts.index') }}" class="nav-link">
                            <span class="fas fa-file-alt mr-1"></span> Posts
                        </a>
                    </li>

                    <!-- small device -->
                    @auth
                        <div class="d-lg-none d-md-none d-sm-block d-xs-block">
                            <li class="nav-item">
                                <a href="{{ route('auth.donators.logout') }}" class="nav-link text-danger">
                                    <span class="fas fa-sign-out-alt"></span> Keluar
                                </a>
                                <form id="account-logout-form" action="{{ route('auth.donators.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </div>
                    @endauth
                </ul>
            </div>
            <div class="d-none d-lg-block">
                @can('view_backend')
                <a href="{{ route('backend.dashboard') }}" class="btn btn-white animate-up-2 mr-3"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
                @endcan

                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
                            $name = explode(" ", \Auth::user()->donator_name);
                            $fist_name = $name[0];
                        ?>
                        <i class="fas fa-user mr-2"></i>hey, {{ $fist_name }} !
                        <i class="fas fa-angle-down fa-lg nav-link-arrow ml-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <!-- <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a> -->
                        <a href="{{ route('auth.donators.logout') }}"
                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4" onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();">
                            <span class="icon icon-sm icon-secondary">
                                <i class="fas fa-sign-out-alt"></i>
                            </span>
                            <div class="ml-4">
                                <span class="text-dark d-block">
                                    Logout
                                </span>
                            </div>
                        </a>
                        <form id="account-logout-form" action="{{ route('auth.donators.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            <!-- on small and xtrasmall -->
            <div class="d-lg-none d-md-none d-sm-block d-xs-block">
                @can('view_backend')
                <a href="{{ route('backend.dashboard') }}" class="btn btn-white"><i class="fas fa-tachometer-alt"></i></a>
                @endcan
            </div>
            <div class="d-flex d-lg-none align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            </div>
        </div>
    </nav>
</header>
