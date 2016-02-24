<div class="subnav-bar">
    <ul class="nav nav-tabs">
        <li><a class="{{ Request::is('standard/*') ? 'active' : ''}}" href="{{url('/standard/'.$project_id)}}">Standard</a></li>
        <li><a class="{{ Request::is('multipost/*') ? 'active' : ''}}" href="{{url('/multipost/'.$project_id)}}">Multipost</a></li>
    </ul>
</div>