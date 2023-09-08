<nav class="navbar navbar-default" role="navigation" style="margin-bottom: 0">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-brand">
            <a href="http://ftn.uns.ac.rs"><img class="logo" src="/assets/images/logo.png"></a>
            <a href="/">ShareBOX v1.0</a>
        </div>
    </div>

    <?php if (isset($user)) {
    ?>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <?php if (!$user->has_role(User::ROLE_NONE)) {
        ?>
            <li class="<?php echo (strpos($section, 'merenja') === 0) ? 'active' : '' ?> dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-dashboard fa-fw"></i>
                    Merenja <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                <li><a href="/merenja" class="<?php echo ($section === 'merenja') ? 'active' : '' ?>">Sva Merenja</a></li>
                <li class="divider"></li>
                <li><a href="/merenja/pregled/staticka/" class="<?php echo ($section === 'staticka') ? 'active' : '' ?>">Statička Merenja</a></li>
                <li><a href="/merenja/pregled/dinamicka/" class="<?php echo ($section === 'dinamicka') ? 'active' : '' ?>">Dinamička Merenja</a></li>
                <li><a href="/merenja/pregled/nedovrsena/" class="<?php echo ($section === 'nedovrsena') ? 'active' : '' ?>">Nedovršena Merenja</a></li>
                <li class="divider"></li>
                <li><a href="/merenja/novo" class="<?php echo ($section === 'novo') ? 'active' : '' ?>">Novo Merenje</a></li>
                </ul>
            </li>
            <?php 
    } ?>
            <?php if ($user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER, User::ROLE_USER_ADMINISTRATOR)) {
        ?>
            <li class="<?php echo ($section === 'korisnici') ? 'active' : '' ?> dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-users fa-fw"></i>
                    Korisnici <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="/korisnici">Pregled</a></li>
                    <li><a href="/korisnici/novo">Novi Korisnik</a></li>
                    <li class="divider"></li>
                    <li><a href="/grupe">Grupe</a></li>
                    <li><a href="/grupe/novo">Nova Grupa</a></li>
                </ul>
            </li>
            <?php 
    } ?>
            <?php if ($user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER)) {
        ?>
            <li class="<?php echo ($section === 'log') ? 'active' : '' ?>">
                <a href="/log">
                    <i class="fa fa-book fa-fw"></i>
                    Log
                </a>
            </li>
            <?php 
    } ?>
            <?php if ($user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_API_ADMINISTRATOR, User::ROLE_DEVELOPER)) {
        ?>
            <li class="<?php echo ($section === 'apikeys') ? 'active' : '' ?>">
                <a href="/apikeys">
                    <i class="fa fa-cogs fa-fw"></i>
                    API ključevi
                </a>
            </li>
            <?php 
    } ?>
            <li class="<?php echo ($section === 'pomoc') ? 'active' : '' ?>">
                <a href="/pomoc">
                    <i class="fa fa-life-bouy fa-fw"></i>
                    Pomoć
                </a>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i><span class="hidden-lg">Nalog</span> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user" role="menu">
                    <li><a href="/profil"><i class="fa fa-user fa-fw"></i> Korisnički Profil</a></li>
                    <li class="divider"></li>
                    <li><a href="/auth/logout/"><i class="fa fa-sign-out fa-fw"></i> Odjavi Se</a></li>
                </ul>
            </li>
        </ul>

    </div>
    <?php 
} ?>
  </div>
</nav>
