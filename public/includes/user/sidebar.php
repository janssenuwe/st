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

            case 'behaviorprofiles':
                $('#toolboxSidebar .nav_point_behaviorprofiles').addClass('uk-active');
                break;

            case 'expertcheck':
                $('#toolboxSidebar .nav_point_expertcheck').addClass('uk-active');
                break;

            case 'checklist':
                $('#toolboxSidebar .nav_point_checklist').addClass('uk-active');
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
		<a href="<?php echo $this->url('homepage/default', array('controller'=> 'Index'));?>">
		<i class="uk-icon-th"></i> Übersicht</a>
	</li>
	
    <li class="uk-nav-divider"></li>
	
    <li class="nav_point_editor">
		<a href="<?php echo $this->url('homepage/default', array('controller' => 'Index', 'action' => 'editor'));?>">
		<i class="uk-icon-font"></i> Text-Optimierer</a>
	</li>
	
	<?php if ($user->rolle == 10): ?>
        <li class="nav_point_behaviorprofiles">
            <a href="<?php echo $this->url('homepage/default', array('controller' => 'BehaviorProfiles', 'action' => 'behaviorprofiles'));?>">
            <i class="uk-icon-user"></i> Profil-Wizard</a>
        </li>
    <?php endif; ?>
	
    <li class="nav_point_expertcheck">
		<a href="<?php echo $this->url('homepage/default', array('controller' => 'Index', 'action' => 'expertcheck'));?>">
		<i class="uk-icon-medkit"></i> Experten-Check</a>
	</li>
    <li class="nav_point_checklist">
		<a href="<?php echo $this->url('homepage/default', array('controller' => 'Index', 'action' => 'checklist'));?>">
		<i class="uk-icon-check-square-o"></i> Checklisten</a>
	</li>
    
	<li class="uk-nav-divider"></li>
	
	<li class="nav_point_contact">
		<a href="<?php echo $this->url('homepage/default', array('controller' => 'Index', 'action' => 'contact'));?>">
		<i class="uk-icon-question-circle"></i> Kontakt/Support</a>
	</li>
	
    
	
	
</ul>