<body>
<nav class="uk-navbar uk-navbar-attached headerBar">
    <div class="uk-container uk-container-center">

        <a class="uk-navbar-brand" href="/check/public/homepage/Index"><img class="" src="/check/public/img/logo_toolbox_admin.png" title="TOOLBOX für Stellenanzeigen" alt="TOOLBOX für Stellenanzeigen"></a>
        <?php if($this->identity()){?>
            <ul class="uk-navbar-nav uk-navbar-flip">
                <li class="uk-active"><a href="/check/public/homepage/Admin/index" class="uk-navbar-nav"><i class="uk-icon-lock"></i> &nbsp;<?= $user->user_vorname." ".$user->user_nachname; ?></a></li>
                <li><a href="<?php echo $this->url('auth/default', array('controller' => 'index', 'action' => 'logout'));?>"><i class="uk-icon-power-off rot"></i> &nbsp;Logout</a></li>
            </ul>
        <?php }?>
    </div>
</nav>
<div class="middle">