<div class="general-header-setting">
<ul class="nav nav-tabs">
<li><a class="{{ Request::is('general-settings') ? 'active' : ''}}" href="{{url('/general-settings')}}">General Settings</a></li>
<li><a class="{{ Request::is('channel-settings') ? 'active' : ''}}" href="{{url('/channel-settings')}}">Social Networks</a></li>
<li><a class="{{ Request::is('coming-soon') ? 'active' : ''}}" href="{{url('/delete-project')}}">Projects</a></li>
</ul>
</div>