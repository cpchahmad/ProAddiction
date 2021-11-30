<ul class="polished-sidebar-menu ml-0 pt-4 p-0 d-md-block">
    <input class="border-dark form-control d-block d-md-none mb-4" type="text" placeholder="Search" aria-label="Search" />
    <li class="
    @if (request()->is('agenthome'))
        active
    @endif"><a href="{{route('agenthome')}}"><span class="fa fa-dashboard"></span> Dashboard</a></li>
    <li class="
    @if (request()->is('agent-stores'))
        active
    @endif"><a href="{{route('agent-stores')}}"><span class="fa fa-dashboard"></span>My Stores</a></li>
    <li class="
    @if (request()->is('agent-order-history'))
        active
    @endif"><a href="{{route('agent-order-history')}}"><span class="fa fa-dashboard"></span>Orders History</a></li>
    <div class="d-block d-md-none">
        <div class="dropdown-divider"></div>
        <li><a href="{{route('logout')}}"> Sign Out</a></li>
    </div>
</ul>
<div class="pl-3 d-none d-md-block position-fixed" style="bottom: 0px">
    <span class="oi oi-cog"></span> Setting
</div>
