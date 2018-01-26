<script>
$(document).ready(function(){
    var arrSitePath     = location.pathname.split("/");
    if(arrSitePath.length == 5)
        $('#toolboxSidebar .nav_point_index').addClass('uk-active');

    for(i in arrSitePath)
    {
        if(i <= 4)
            continue;
        console.log(arrSitePath[i].toLowerCase());
        switch (arrSitePath[i].toLowerCase())
        {
            case 'index':
                $('#toolboxSidebar .nav_point_index').addClass('uk-active');
                break;

            case 'editor':
                $('#toolboxSidebar .nav_point_editor').addClass('uk-active');
                break;

            case 'create':
            case 'update':
            case 'showusers':
                $('#toolboxSidebar .nav_point_user').addClass('uk-active');
                break;

            case 'showtexttable':
            case 'showtextchecktables':
                $('#toolboxSidebar .nav_point_setting').addClass('uk-active');
                break;

            case 'behaviorprofilesadmin':
                $('#toolboxSidebar .nav_point_behaviorprofilesadmin').addClass('uk-active');
                break;

            case 'editbehaviorprofile':
                $('#toolboxSidebar .nav_point_editbehaviorprofile').addClass('uk-active');
                break;

            case 'expertcheck':
                $('#toolboxSidebar .nav_point_expertcheck').addClass('uk-active');
                break;

            case 'contact':
                $('#toolboxSidebar .nav_point_contact').addClass('uk-active');
                break;
        }
    }
});
</script>
<ul id="toolboxSidebar" class="uk-nav uk-nav-side">
    <li class="nav_point_index">
        <a href="<?php echo $this->url('homepage/default', array('controller'=> 'Admin'));?>"><i class="uk-icon-th"></i> Übersicht</a>
    </li>
    <li class="uk-nav-divider"></li>
    <li class="nav_point_editor">
        <a href="<?php echo $this->url('homepage/default', array('controller' => 'Admin', 'action' => 'editor'));?>"><i class="uk-icon-font"></i> Text-Optimierer</a>
    </li>
	<li class="nav_point_behaviorprofilesadmin">
		<a href="<?php echo $this->url('homepage/default', array('controller' => 'BehaviorProfiles', 'action' => 'behaviorprofilesadmin'));?>"><i class="uk-icon-user"></i> Profil-Wizard</a>
    </li>
    <li class="uk-nav-divider"></li>
    <li class="nav_point_user">
        <a href="<?php echo $this->url('homepage/default', array('controller' => 'Admin', 'action' => 'showusers', 'id' => 1));?>?user_name=&user_adresse=&user_email=&user_firma=">
            <i class="uk-icon-users"></i> Benutzer
        </a>
    </li>
    <li class="nav_point_setting">
            <a href="<?php echo $this->url('homepage/default', array('controller' => 'Admin', 'action' => 'showtextchecktables'));?>">
                <i class="uk-icon-gear"></i> Einstellungen verwalten
            </a>
    </li>
    <li class="uk-nav-divider"></li>
    <li class="nav_point_expertcheck">
        <a href="<?php echo $this->url('homepage/default', array('controller' => 'Admin', 'action' => 'expertcheck'));?>"><i class="uk-icon-edit"></i> Experten Check</a>
    </li>

    <li class="nav_point_contact">
        <a href="<?php echo $this->url('homepage/default', array('controller' => 'Admin', 'action' => 'contact'));?>"><i class="uk-icon-edit"></i> Kontakt</a>
    </li>

    <li class="uk-nav-divider"></li>
    <li class="nav_point_editbehaviorprofile">
        <a href="<?php echo $this->url('homepage/default', array('controller' => 'Admin', 'action' => 'editbehaviorprofile', 'id' => 1));?>">
            <i class="uk-icon-users"></i> Verhaltensprofile bearb...
        </a>
    </li>
</ul>

