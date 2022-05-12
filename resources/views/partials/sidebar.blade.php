<div id="app-sidepanel" class="app-sidepanel">
    <div id="sidepanel-drop" class="sidepanel-drop"></div>
    <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        <div class="app-branding">
            <a class="app-logo" href="/"><img class="logo-icon me-2" src="assets/images/app-logo.svg"
                    alt="logo"><span class="logo-text">ONEMART GSM</span></a>
        </div>
        <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
            <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                @include('partials.sidebar-item', ['libelle' => 'Configurations', 'link' => route('configuration')])
            </ul>
        </nav>

    </div>
</div>