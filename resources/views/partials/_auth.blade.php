@if (Auth::check())
    <dropdown>
        <img src="{{ Auth::user()->present()->avatar(20) }}" alt="">
        <strong>{{ Auth::user()->name }}</strong>

        <ul slot="dropdown-menu" class="dropdown-menu auth-dropdown">
            <li>
                <div class="navbar-login">
                    <div class="row">
                        <div class="col-lg-4 hidden-xs">
                            <p class="text-center">
                                <img src="{{ Auth::user()->present()->avatar(87) }}" alt="">
                            </p>
                        </div>
                        <div class="col-lg-8">
                            <p class="text-left hidden-xs"><strong>{{ Auth::user()->name }}</strong></p>
                            <p class="text-left">
                                <a href="/account" class="btn btn-primary btn-block btn-sm">My Account</a>
                                @if (Auth::user()->hasRole('admin'))
                                    <a href="/admin" class="btn btn-default btn-block btn-sm"><i class="fa fa-lock"></i> Admin</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            <li class="divider"></li>
            <li>
                <div class="navbar-login navbar-login-session">
                    <div class="row">
                        <div class="col-lg-12">
                            <p>
                                <a href="/logout" class="btn btn-danger btn-block">Logout</a>
                            </p>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </dropdown>
@else
    <li>
        <a href="/login" class="">Login</a>
    </li>
@endif
