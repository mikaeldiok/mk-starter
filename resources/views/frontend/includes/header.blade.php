
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
  <div class="container">
    <a class="navbar-brand" href="/">PEDULI</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="oi oi-menu"></span> Menu
    </button>

    <div class="collapse navbar-collapse" id="ftco-nav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active"><a href="index.html" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="how-it-works.html" class="nav-link">How It Works</a></li>
        <li class="nav-item"><a href="donate.html" class="nav-link">Donate</a></li>
        <li class="nav-item"><a href="gallery.html" class="nav-link">Gallery</a></li>
        <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li>
        <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
        @if(Auth::guard('donator')->check())
          <li class="nav-item"><a href="{{route('auth.donators.logout')}}" class="nav-link">LOGOUT USER</a>
            <form id="account-logout-form" action="{{ route('auth.donators.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        @elseif(Auth::guard('web')->check())
          <li class="nav-item"><a href="{{route('logout')}}" class="nav-link">LOGOUT ADMIN</a>
            <form id="account-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        @else
          <li class="nav-item"><a href="{{route('auth.donators.login')}}" class="btn btn-sm btn-warning nav-button">log in</a></li>
        @endif
      </ul>
    </div>
  </div>
</nav>
<!-- END nav -->