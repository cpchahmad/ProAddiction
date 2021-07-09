<nav class="navbar navbar-expand p-0">
    <span style="width: 100%;">
            <a class="navbar-brand text-center col-xs-12 col-md-3 col-lg-2 mr-0" href="index.html">         <img src="{{asset('css/polished-logo-small.png')}}" alt="logo" width="42px">         Proaddiction</a>
        @if (!request()->is('login') && !request()->is('register'))
        <button class="btn btn-link d-block d-md-none" data-toggle="collapse" data-target="#sidebar-nav" role="button" >
                <span class="oi oi-menu"></span>
            </button>
        <div class="dropdown d-none d-md-block" style="float: right;">
            {{--<img class="d-none d-lg-inline rounded-circle ml-1" width="32px" src="{{asset('images/azamuddin.jpg')}}" alt="MA"/>--}}
            <button class="btn btn-link btn-link-primary dropdown-toggle" id="navbar-dropdown" data-toggle="dropdown" >
                {{--{{$userName}}--}}
                username
            </button>
            <div class="dropdown-menu dropdown-menu-right" id="navbar-dropdown">
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Sign Out</button>
                </form>


            </div>
        </div>
        @endif
        </span>
</nav>
