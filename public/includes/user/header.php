<body>
<nav class="uk-navbar uk-navbar-attached headerBar">
    <div class="uk-container uk-container-center">

        <a class="uk-navbar-brand" href="<?php echo ($user->rolle == 100 ? $this->url('homepage/default', array('controller'=> 'Admin')) : $this->url('homepage/default', array('controller' => 'index')));?>"><img class="" src="/check/public/img/logo_toolbox_user.png" title="TOOLBOX by JOBquick" alt="TOOLBOX für Stellenanzeigen Logo"></a>
        <?php if($this->identity()){?>
            <ul class="uk-navbar-nav uk-navbar-flip">
                <li class="uk-active"><a href="/check/public/homepage/Admin/index" class="uk-navbar-nav"><i class="uk-icon-lock"></i> &nbsp;<?= $user->user_vorname." ".utf8_encode($user->user_nachname); ?></a></li>
                <li><a href="<?php echo $this->url('auth/default', array('controller' => 'index', 'action' => 'logout'));?>"><i class="uk-icon-power-off rot"></i> &nbsp;Logout</a></li>
            </ul>
        <?php }?>
    </div>
</nav>
<div class="middle">