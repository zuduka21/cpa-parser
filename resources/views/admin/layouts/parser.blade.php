<!doctype html>
<html lang="en" class="no-focus">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="description"
          content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Codebase">
    <meta property="og:description"
          content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{asset('/media/favicons/favicon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('/media/favicons/favicon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/media/favicons/apple-touch-icon-180x180.png')}}">
    <!-- END Icons -->

    <!-- Stylesheets -->

@yield('pagejsplugins')
<!-- Fonts and Codebase framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
    <link rel="stylesheet" id="css-main" href="{{asset('/css/codebase.min.css')}}">
    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <!-- END Stylesheets -->

</head>
<body>

<!-- Page Container -->
<!--
    Available classes for #page-container:

GENERIC

    'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

SIDEBAR & SIDE OVERLAY

    'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
    'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
    'sidebar-inverse'                           Dark themed sidebar

    'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                            Visible Side Overlay by default

    'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

    'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

HEADER

    ''                                          Static Header if no class is added
    'page-header-fixed'                         Fixed Header

HEADER STYLE

    ''                                          Classic Header style if no class is added
    'page-header-modern'                        Modern Header style
    'page-header-inverse'                       Dark themed Header (works only with classic Header style)
    'page-header-glass'                         Light themed Header with transparency by default
                                                (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
    'page-header-glass page-header-inverse'     Dark themed Header with transparency by default
                                                (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

MAIN CONTENT LAYOUT

    ''                                          Full width Main Content if no class is added
    'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
-->
<div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-modern main-content-boxed">

    <!-- Sidebar -->
    <!--
        Helper classes

        Adding .sidebar-mini-hide to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
        Adding .sidebar-mini-show to an element will make it visible (opacity: 1) when the sidebar is in mini mode
            If you would like to disable the transition, just add the .sidebar-mini-notrans along with one of the previous 2 classes

        Adding .sidebar-mini-hidden to an element will hide it when the sidebar is in mini mode
        Adding .sidebar-mini-visible to an element will show it only when the sidebar is in mini mode
            - use .sidebar-mini-visible-b if you would like to be a block when visible (display: block)
    -->
    <nav id="sidebar">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="content-header content-header-fullrow px-15">
                <!-- Mini Mode -->
                <div class="content-header-section sidebar-mini-visible-b">
                    <!-- Logo -->
                    <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                            </span>
                    <!-- END Logo -->
                </div>
                <!-- END Mini Mode -->

                <!-- Normal Mode -->
                <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r"
                            data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                    <!-- END Close Sidebar -->

                    <!-- Logo -->
                    <div class="content-header-item">
                        <a class="link-effect font-w700" href="index.html">
                            <i class="si si-fire text-primary"></i>
                            <span class="font-size-xl text-dual-primary-dark">CPA</span><span
                                    class="font-size-xl text-primary"> Parser</span>
                        </a>
                    </div>
                    <!-- END Logo -->
                </div>
                <!-- END Normal Mode -->
            </div>
            <!-- END Side Header -->

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li>
                        <a href="{{route('admin_dashboard_index')}}"><i class="si si-cup"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                    </li>

                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-file"></i><span
                                class="sidebar-mini-hide">Reports</span></a>
                        <ul>
                            @if(Auth::user()->checkRole('admin'))
                            <li>
                                <a href="{{route('admin_reports_general')}}">General report</a>
                            </li>
                            <li>
                                <a href="{{route('admin_reports_detail')}}">Detail report</a>
                            </li>
                            @elseif(Auth::user()->checkRole('user'))
                            <li>
                                <a href="{{route('user_index')}}">Report</a>
                            </li>
                             @endif
                        </ul>
                    </li>
                    @if(Auth::user()->checkRole('admin'))
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cog"></i><span
                                        class="sidebar-mini-hide">Settings</span></a>
                            <ul>
                                <li>
                                    <a href="{{route('admin_users_index')}}">Users</a>
                                </li>
                                <li>
                                    <a href="{{route('admin_partners_index')}}">Parsers</a>
                                </li>
                                <li>
                                    <a href="{{route('admin_countries_index')}}">Countries</a>
                                </li>
                            </ul>
                        </li>
                    <li>
                        <a href="{{route('admin_logs_index')}}"><i class="fa fa-file"></i><span class="sidebar-mini-hide">Logs</span></a>
                    </li>
                    @endif
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- Sidebar Content -->
    </nav>
    <!-- END Sidebar -->

    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="content-header-section">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout"
                        data-action="sidebar_toggle">
                    <i class="fa fa-navicon"></i>
                </button>
                <!-- END Toggle Sidebar -->
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="content-header-section">
                <!-- User Dropdown -->

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user d-sm-none"></i>
                        <span class="d-none d-sm-inline-block">{{ Auth::user()->name }}</span>
                        <i class="fa fa-angle-down ml-5"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right min-width-200"
                         aria-labelledby="page-header-user-dropdown">
                        <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">{{Auth::user()->roles->first()->name}}</h5>
                        {{--                        <a class="dropdown-item" href="be_pages_generic_profile.html">--}}
                        {{--                            <i class="si si-user mr-5"></i> Profile--}}
                        {{--                        </a>--}}
                        {{--                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.html">--}}
                        {{--                            <span><i class="si si-envelope-open mr-5"></i> Inbox</span>--}}
                        {{--                            <span class="badge badge-primary">3</span>--}}
                        {{--                        </a>--}}
                        {{--                        <div class="dropdown-divider"></div>--}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="si si-logout mr-5"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
                <!-- END User Dropdown -->
            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Loader -->
        <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
        <div id="page-header-loader" class="overlay-header bg-primary">
            <div class="content-header content-header-fullrow text-center">
                <div class="content-header-item">
                    <i class="fa fa-sun-o fa-spin text-white"></i>
                </div>
            </div>
        </div>
        <!-- END Header Loader -->
    </header>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Content -->
        <div class="content">
            <div id="all-massages">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h4 class="font-weight-bold mb-3">Ooops... Errors occurred</h4>
                        <ul class="m-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @yield('content')
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="opacity-0">
        <div class="content py-20 font-size-xs clearfix">
            <div class="float-right">
                Crafted with <i class="fa fa-heart text-pulse"></i> by
                <a class="font-w600" href="https://1.envato.market/ydb" target="_blank">pixelcave</a>
            </div>
            <div class="float-left">
                <a class="font-w600" href="https://1.envato.market/95j" target="_blank">Codebase 3.1</a> &copy;
                <span class="js-year-copy"></span>
            </div>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->

<!--
    Codebase JS Core

    Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
    to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

    If you like, you could also include them separately directly from the assets/js/core folder in the following
    order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

    assets/js/core/jquery.min.js
    assets/js/core/bootstrap.bundle.min.js
    assets/js/core/simplebar.min.js
    assets/js/core/jquery-scrollLock.min.js
    assets/js/core/jquery.appear.min.js
    assets/js/core/jquery.countTo.min.js
    assets/js/core/js.cookie.min.js
-->
<script src="{{asset('/js/codebase.core.min.js')}}"></script>

<!--
    Codebase JS

    Custom functionality including Blocks/Layout API as well as other vital and optional helpers
    webpack is putting everything together at assets/_es6/main/app.js
-->
<script src="{{asset('/js/codebase.app.min.js')}}"></script>
@yield('pagescripts')
</body>
</html>
