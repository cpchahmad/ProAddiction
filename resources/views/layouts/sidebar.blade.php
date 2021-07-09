<ul class="polished-sidebar-menu ml-0 pt-4 p-0 d-md-block">
    <input class="border-dark form-control d-block d-md-none mb-4" type="text" placeholder="Search" aria-label="Search" />
    <li class="
    @if (request()->is('/'))
        active
    @endif"><a href="{{route('home')}}"><span class="fa fa-dashboard"></span> Dashboard</a></li>
    <li class="
    @if (request()->is('customers'))
        active
    @endif"><a href="{{route('customers')}}"><span class="fa fa-users"></span> Customers</a></li>
    <li class="
    @if (request()->is('orders'))
        active
    @endif" ><a href="{{route('orders')}}"><span class="fa fa-first-order"></span> Orders</a></li>
    <li class="
    @if (request()->is('analytics'))
        active
    @endif" ><a href="{{route('analytics')}}"><span class="fa fa-bar-chart" aria-hidden="true"></span>Analytics</a></li>
    <div class="d-block d-md-none">
        <div class="dropdown-divider"></div>
        <li><a href="#"> Profile</a></li>
        <li><a href="#"> Setting</a></li>
        <li><a href="#"> Sign Out</a></li>
    </div>
</ul>
<div class="pl-3 d-none d-md-block position-fixed" style="bottom: 0px">
    <span class="oi oi-cog"></span> Setting
</div>
