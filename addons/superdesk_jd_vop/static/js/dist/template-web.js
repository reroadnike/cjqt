





<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
  <link rel="dns-prefetch" href="https://assets-cdn.github.com">
  <link rel="dns-prefetch" href="https://avatars0.githubusercontent.com">
  <link rel="dns-prefetch" href="https://avatars1.githubusercontent.com">
  <link rel="dns-prefetch" href="https://avatars2.githubusercontent.com">
  <link rel="dns-prefetch" href="https://avatars3.githubusercontent.com">
  <link rel="dns-prefetch" href="https://github-cloud.s3.amazonaws.com">
  <link rel="dns-prefetch" href="https://user-images.githubusercontent.com/">



  <link crossorigin="anonymous" href="https://assets-cdn.github.com/assets/frameworks-c9193575f18b28be82c0a963e144ff6fa7a809dd8ae003a1d1e5979bed3f7f00.css" integrity="sha256-yRk1dfGLKL6CwKlj4UT/b6eoCd2K4AOh0eWXm+0/fwA=" media="all" rel="stylesheet" />
  <link crossorigin="anonymous" href="https://assets-cdn.github.com/assets/github-6536549f7712e1f35def45bde8e2dbb9c4304a1dd4d28e3368cceaab0deb093a.css" integrity="sha256-ZTZUn3cS4fNd70W96OLbucQwSh3U0o4zaMzqqw3rCTo=" media="all" rel="stylesheet" />
  
  
  
  

  <meta name="viewport" content="width=device-width">
  
  <title>art-template/template-web.js at master · aui/art-template</title>
  <link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="GitHub">
  <link rel="fluid-icon" href="https://github.com/fluidicon.png" title="GitHub">
  <meta property="fb:app_id" content="1401488693436528">

    
    <meta content="https://avatars0.githubusercontent.com/u/1791748?s=400&amp;v=4" property="og:image" /><meta content="GitHub" property="og:site_name" /><meta content="object" property="og:type" /><meta content="aui/art-template" property="og:title" /><meta content="https://github.com/aui/art-template" property="og:url" /><meta content="art-template - High performance JavaScript templating engine" property="og:description" />

  <link rel="assets" href="https://assets-cdn.github.com/">
  <link rel="web-socket" href="wss://live.github.com/_sockets/VjI6MjE2NjAzODk0OjdlNjU1OTVjZjU4MTdmMmIxZGVkZjBjY2IyZjAwY2Y2MGNhNGVkMDc1YWE0NDY5ZTI3ZmMzODVmOGUwMDMxNDg=--0297b06fbf666b5e4fa7488534e3a805a13198b0">
  <meta name="pjax-timeout" content="1000">
  <link rel="sudo-modal" href="/sessions/sudo_modal">
  <meta name="request-id" content="E241:2338:BAB176:10202EA:5A1E9CA4" data-pjax-transient>
  

  <meta name="selected-link" value="repo_source" data-pjax-transient>

  <meta name="google-site-verification" content="KT5gs8h0wvaagLKAVWq8bbeNwnZZK1r1XQysX3xurLU">
<meta name="google-site-verification" content="ZzhVyEFwb7w3e0-uOTltm8Jsck2F5StVihD0exw2fsA">
    <meta name="google-analytics" content="UA-3769691-2">

<meta content="collector.githubapp.com" name="octolytics-host" /><meta content="github" name="octolytics-app-id" /><meta content="https://collector.githubapp.com/github-external/browser_event" name="octolytics-event-url" /><meta content="E241:2338:BAB176:10202EA:5A1E9CA4" name="octolytics-dimension-request_id" /><meta content="sea" name="octolytics-dimension-region_edge" /><meta content="iad" name="octolytics-dimension-region_render" /><meta content="3094325" name="octolytics-actor-id" /><meta content="rain16881" name="octolytics-actor-login" /><meta content="caa346abc54ec4d59810ce4a5c191d79f9133f2490ec3e0188a62c81d1992a91" name="octolytics-actor-hash" />
<meta content="/&lt;user-name&gt;/&lt;repo-name&gt;/blob/show" data-pjax-transient="true" name="analytics-location" />




  <meta class="js-ga-set" name="dimension1" content="Logged In">


  

      <meta name="hostname" content="github.com">
  <meta name="user-login" content="rain16881">

      <meta name="expected-hostname" content="github.com">
    <meta name="js-proxy-site-detection-payload" content="ZmFiNmJkZjQzODIxZTczMmU2NjdjMWI2ZTk2MTY5OWZiM2IwZWJiNmI3NzliYTQyOGMwNjRmMTlmMDcyNzlmZnx7InJlbW90ZV9hZGRyZXNzIjoiMTEzLjkxLjEyNS4yMDIiLCJyZXF1ZXN0X2lkIjoiRTI0MToyMzM4OkJBQjE3NjoxMDIwMkVBOjVBMUU5Q0E0IiwidGltZXN0YW1wIjoxNTExOTU1NjIwLCJob3N0IjoiZ2l0aHViLmNvbSJ9">

    <meta name="enabled-features" content="UNIVERSE_BANNER,FREE_TRIALS">

  <meta name="html-safe-nonce" content="8af2e656f598e913b0ddbe5ae474ce306e8f589e">

  <meta http-equiv="x-pjax-version" content="0e2a4c1df76905f111dfbb9010eb7ec6">
  

      <link href="https://github.com/aui/art-template/commits/master.atom" rel="alternate" title="Recent Commits to art-template:master" type="application/atom+xml">

  <meta name="description" content="art-template - High performance JavaScript templating engine">
  <meta name="go-import" content="github.com/aui/art-template git https://github.com/aui/art-template.git">

  <meta content="1791748" name="octolytics-dimension-user_id" /><meta content="aui" name="octolytics-dimension-user_login" /><meta content="4534540" name="octolytics-dimension-repository_id" /><meta content="aui/art-template" name="octolytics-dimension-repository_nwo" /><meta content="true" name="octolytics-dimension-repository_public" /><meta content="false" name="octolytics-dimension-repository_is_fork" /><meta content="4534540" name="octolytics-dimension-repository_network_root_id" /><meta content="aui/art-template" name="octolytics-dimension-repository_network_root_nwo" /><meta content="false" name="octolytics-dimension-repository_explore_github_marketplace_ci_cta_shown" />


    <link rel="canonical" href="https://github.com/aui/art-template/blob/master/lib/template-web.js" data-pjax-transient>


  <meta name="browser-stats-url" content="https://api.github.com/_private/browser/stats">

  <meta name="browser-errors-url" content="https://api.github.com/_private/browser/errors">

  <link rel="mask-icon" href="https://assets-cdn.github.com/pinned-octocat.svg" color="#000000">
  <link rel="icon" type="image/x-icon" class="js-site-favicon" href="https://assets-cdn.github.com/favicon.ico">

<meta name="theme-color" content="#1e2327">


  <meta name="u2f-support" content="true">

  </head>

  <body class="logged-in env-production page-blob">
    

  <div class="position-relative js-header-wrapper ">
    <a href="#start-of-content" tabindex="1" class="bg-black text-white p-3 show-on-focus js-skip-to-content">Skip to content</a>
    <div id="js-pjax-loader-bar" class="pjax-loader-bar"><div class="progress"></div></div>

    
    
    



        
<header class="Header  f5" role="banner">
  <div class="d-flex px-3 flex-justify-between container-lg">
    <div class="d-flex flex-justify-between">
      <a class="header-logo-invertocat" href="https://github.com/" data-hotkey="g d" aria-label="Homepage" data-ga-click="Header, go to dashboard, icon:logo">
  <svg aria-hidden="true" class="octicon octicon-mark-github" height="32" version="1.1" viewBox="0 0 16 16" width="32"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
</a>


    </div>

    <div class="HeaderMenu d-flex flex-justify-between flex-auto">
      <div class="d-flex">
            <div class="">
              <div class="header-search scoped-search site-scoped-search js-site-search" role="search">
  <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/aui/art-template/search" class="js-site-search-form" data-scoped-search-url="/aui/art-template/search" data-unscoped-search-url="/search" method="get"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /></div>
    <label class="form-control header-search-wrapper js-chromeless-input-container">
        <a href="/aui/art-template/blob/master/lib/template-web.js" class="header-search-scope no-underline">This repository</a>
      <input type="text"
        class="form-control header-search-input js-site-search-focus js-site-search-field is-clearable"
        data-hotkey="s"
        name="q"
        value=""
        placeholder="Search"
        aria-label="Search this repository"
        data-unscoped-placeholder="Search GitHub"
        data-scoped-placeholder="Search"
        autocapitalize="off">
        <input type="hidden" class="js-site-search-type-field" name="type" >
    </label>
</form></div>

            </div>

          <ul class="d-flex pl-2 flex-items-center text-bold list-style-none" role="navigation">
            <li>
              <a href="/pulls" aria-label="Pull requests you created" class="js-selected-navigation-item HeaderNavlink px-2" data-ga-click="Header, click, Nav menu - item:pulls context:user" data-hotkey="g p" data-selected-links="/pulls /pulls/assigned /pulls/mentioned /pulls">
                Pull requests
</a>            </li>
            <li>
              <a href="/issues" aria-label="Issues you created" class="js-selected-navigation-item HeaderNavlink px-2" data-ga-click="Header, click, Nav menu - item:issues context:user" data-hotkey="g i" data-selected-links="/issues /issues/assigned /issues/mentioned /issues">
                Issues
</a>            </li>
                <li>
                  <a href="/marketplace" class="js-selected-navigation-item HeaderNavlink px-2" data-ga-click="Header, click, Nav menu - item:marketplace context:user" data-selected-links=" /marketplace">
                    Marketplace
</a>                </li>
            <li>
              <a href="/explore" class="js-selected-navigation-item HeaderNavlink px-2" data-ga-click="Header, click, Nav menu - item:explore" data-selected-links="/explore /trending /trending/developers /integrations /integrations/feature/code /integrations/feature/collaborate /integrations/feature/ship showcases showcases_search showcases_landing /explore">
                Explore
</a>            </li>
          </ul>
      </div>

      <div class="d-flex">
        
<ul class="user-nav d-flex flex-items-center list-style-none" id="user-links">
  <li class="dropdown js-menu-container">
    <span class="d-inline-block  px-2">
      
    <a href="/notifications" aria-label="You have unread notifications" class="notification-indicator tooltipped tooltipped-s  js-socket-channel js-notification-indicator" data-channel="notification-changed:3094325" data-ga-click="Header, go to notifications, icon:unread" data-hotkey="g n">
        <span class="mail-status unread"></span>
        <svg aria-hidden="true" class="octicon octicon-bell" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path fill-rule="evenodd" d="M14 12v1H0v-1l.73-.58c.77-.77.81-2.55 1.19-4.42C2.69 3.23 6 2 6 2c0-.55.45-1 1-1s1 .45 1 1c0 0 3.39 1.23 4.16 5 .38 1.88.42 3.66 1.19 4.42l.66.58H14zm-7 4c1.11 0 2-.89 2-2H5c0 1.11.89 2 2 2z"/></svg>
</a>
    </span>
  </li>

  <li class="dropdown js-menu-container">
    <details class="dropdown-details details-reset js-dropdown-details d-flex px-2 flex-items-center">
      <summary class="HeaderNavlink"
         aria-label="Create new…"
         data-ga-click="Header, create new, icon:add">
        <svg aria-hidden="true" class="octicon octicon-plus float-left mr-1 mt-1" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 9H7v5H5V9H0V7h5V2h2v5h5z"/></svg>
        <span class="dropdown-caret mt-1"></span>
      </summary>

      <ul class="dropdown-menu dropdown-menu-sw">
        
<a class="dropdown-item" href="/new" data-ga-click="Header, create new repository">
  New repository
</a>

  <a class="dropdown-item" href="/new/import" data-ga-click="Header, import a repository">
    Import repository
  </a>

<a class="dropdown-item" href="https://gist.github.com/" data-ga-click="Header, create new gist">
  New gist
</a>

  <a class="dropdown-item" href="/organizations/new" data-ga-click="Header, create new organization">
    New organization
  </a>



  <div class="dropdown-divider"></div>
  <div class="dropdown-header">
    <span title="aui/art-template">This repository</span>
  </div>
    <a class="dropdown-item" href="/aui/art-template/issues/new" data-ga-click="Header, create new issue">
      New issue
    </a>

      </ul>
    </details>
  </li>

  <li class="dropdown js-menu-container">

    <details class="dropdown-details details-reset js-dropdown-details d-flex pl-2 flex-items-center">
      <summary class="HeaderNavlink name mt-1"
        aria-label="View profile and more"
        data-ga-click="Header, show menu, icon:avatar">
        <img alt="@rain16881" class="avatar float-left mr-1" src="https://avatars1.githubusercontent.com/u/3094325?s=40&amp;v=4" height="20" width="20">
        <span class="dropdown-caret"></span>
      </summary>

      <ul class="dropdown-menu dropdown-menu-sw">
        <li class="dropdown-header header-nav-current-user css-truncate">
          Signed in as <strong class="css-truncate-target">rain16881</strong>
        </li>

        <li class="dropdown-divider"></li>

        <li><a class="dropdown-item" href="/rain16881" data-ga-click="Header, go to profile, text:your profile">
          Your profile
        </a></li>
        <li><a class="dropdown-item" href="/rain16881?tab=stars" data-ga-click="Header, go to starred repos, text:your stars">
          Your stars
        </a></li>
          <li><a class="dropdown-item" href="https://gist.github.com/" data-ga-click="Header, your gists, text:your gists">Your Gists</a></li>

        <li class="dropdown-divider"></li>

        <li><a class="dropdown-item" href="https://help.github.com" data-ga-click="Header, go to help, text:help">
          Help
        </a></li>

        <li><a class="dropdown-item" href="/settings/profile" data-ga-click="Header, go to settings, icon:settings">
          Settings
        </a></li>

        <li><!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/logout" class="logout-form" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="VQKMp0gEg76d14WiuGURaM+ohiis4PBU2Yj68bkTs7qytavNtVSh8i4aXMJT2axQvt9C+4nfVT7LqxdivbjXCA==" /></div>
          <button type="submit" class="dropdown-item dropdown-signout" data-ga-click="Header, sign out, icon:logout">
            Sign out
          </button>
        </form></li>
      </ul>
    </details>
  </li>
</ul>


        <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/logout" class="sr-only right-0" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="f1Leze+ZnqqTw/bwbG4cPhsLY7zxEGucIMt05m67siOY5fmnEsm85iAOL5CH0qEGanynb9QvzvYy6Jl1ahDWkQ==" /></div>
          <button type="submit" class="dropdown-item dropdown-signout" data-ga-click="Header, sign out, icon:logout">
            Sign out
          </button>
</form>      </div>
    </div>
  </div>
</header>


      

  </div>

  <div id="start-of-content" class="show-on-focus"></div>

    <div id="js-flash-container">
</div>



  <div role="main">
        <div itemscope itemtype="http://schema.org/SoftwareSourceCode">
    <div id="js-repo-pjax-container" data-pjax-container>
      





    <div class="pagehead repohead instapaper_ignore readability-menu experiment-repo-nav ">
      <div class="repohead-details-container clearfix container ">

        <ul class="pagehead-actions">
  <li>
        <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/notifications/subscribe" class="js-social-container" data-autosubmit="true" data-remote="true" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="mAdpk4pkhlgi/Sda/pgXRa3JBiSZ/udco37AOwu869WEtj7qKTeJr9pfJ6x3uv9N/TxlcvrvYpbpOZdUMbNHug==" /></div>      <input class="form-control" id="repository_id" name="repository_id" type="hidden" value="4534540" />

        <div class="select-menu js-menu-container js-select-menu">
          <a href="/aui/art-template/subscription"
            class="btn btn-sm btn-with-count select-menu-button js-menu-target"
            role="button"
            aria-haspopup="true"
            aria-expanded="false"
            aria-label="Toggle repository notifications menu"
            data-ga-click="Repository, click Watch settings, action:blob#show">
            <span class="js-select-button">
                <svg aria-hidden="true" class="octicon octicon-eye" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M8.06 2C3 2 0 8 0 8s3 6 8.06 6C13 14 16 8 16 8s-3-6-7.94-6zM8 12c-2.2 0-4-1.78-4-4 0-2.2 1.8-4 4-4 2.22 0 4 1.8 4 4 0 2.22-1.78 4-4 4zm2-4c0 1.11-.89 2-2 2-1.11 0-2-.89-2-2 0-1.11.89-2 2-2 1.11 0 2 .89 2 2z"/></svg>
                Watch
            </span>
          </a>
            <a class="social-count js-social-count"
              href="/aui/art-template/watchers"
              aria-label="483 users are watching this repository">
              483
            </a>

        <div class="select-menu-modal-holder">
          <div class="select-menu-modal subscription-menu-modal js-menu-content">
            <div class="select-menu-header js-navigation-enable" tabindex="-1">
              <svg aria-label="Close" class="octicon octicon-x js-menu-close" height="16" role="img" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48z"/></svg>
              <span class="select-menu-title">Notifications</span>
            </div>

              <div class="select-menu-list js-navigation-container" role="menu">

                <div class="select-menu-item js-navigation-item selected" role="menuitem" tabindex="0">
                  <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
                  <div class="select-menu-item-text">
                    <input checked="checked" id="do_included" name="do" type="radio" value="included" />
                    <span class="select-menu-item-heading">Not watching</span>
                    <span class="description">Be notified when participating or @mentioned.</span>
                    <span class="js-select-button-text hidden-select-button-text">
                      <svg aria-hidden="true" class="octicon octicon-eye" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M8.06 2C3 2 0 8 0 8s3 6 8.06 6C13 14 16 8 16 8s-3-6-7.94-6zM8 12c-2.2 0-4-1.78-4-4 0-2.2 1.8-4 4-4 2.22 0 4 1.8 4 4 0 2.22-1.78 4-4 4zm2-4c0 1.11-.89 2-2 2-1.11 0-2-.89-2-2 0-1.11.89-2 2-2 1.11 0 2 .89 2 2z"/></svg>
                      Watch
                    </span>
                  </div>
                </div>

                <div class="select-menu-item js-navigation-item " role="menuitem" tabindex="0">
                  <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
                  <div class="select-menu-item-text">
                    <input id="do_subscribed" name="do" type="radio" value="subscribed" />
                    <span class="select-menu-item-heading">Watching</span>
                    <span class="description">Be notified of all conversations.</span>
                    <span class="js-select-button-text hidden-select-button-text">
                      <svg aria-hidden="true" class="octicon octicon-eye" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M8.06 2C3 2 0 8 0 8s3 6 8.06 6C13 14 16 8 16 8s-3-6-7.94-6zM8 12c-2.2 0-4-1.78-4-4 0-2.2 1.8-4 4-4 2.22 0 4 1.8 4 4 0 2.22-1.78 4-4 4zm2-4c0 1.11-.89 2-2 2-1.11 0-2-.89-2-2 0-1.11.89-2 2-2 1.11 0 2 .89 2 2z"/></svg>
                        Unwatch
                    </span>
                  </div>
                </div>

                <div class="select-menu-item js-navigation-item " role="menuitem" tabindex="0">
                  <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
                  <div class="select-menu-item-text">
                    <input id="do_ignore" name="do" type="radio" value="ignore" />
                    <span class="select-menu-item-heading">Ignoring</span>
                    <span class="description">Never be notified.</span>
                    <span class="js-select-button-text hidden-select-button-text">
                      <svg aria-hidden="true" class="octicon octicon-mute" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M8 2.81v10.38c0 .67-.81 1-1.28.53L3 10H1c-.55 0-1-.45-1-1V7c0-.55.45-1 1-1h2l3.72-3.72C7.19 1.81 8 2.14 8 2.81zm7.53 3.22l-1.06-1.06-1.97 1.97-1.97-1.97-1.06 1.06L11.44 8 9.47 9.97l1.06 1.06 1.97-1.97 1.97 1.97 1.06-1.06L13.56 8l1.97-1.97z"/></svg>
                        Stop ignoring
                    </span>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>
</form>
  </li>

  <li>
    
  <div class="js-toggler-container js-social-container starring-container ">
    <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/aui/art-template/unstar" class="starred js-social-form" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="5PF/V1BpoqrggbRA2edYDtDfNmZRPS1CX0i8n+X72taE062AKB+wKD7TnIcebX3JAznVEgzETEyy1VZVy4XVjQ==" /></div>
      <input type="hidden" name="context" value="repository"></input>
      <button
        type="submit"
        class="btn btn-sm btn-with-count js-toggler-target"
        aria-label="Unstar this repository" title="Unstar aui/art-template"
        data-ga-click="Repository, click unstar button, action:blob#show; text:Unstar">
        <svg aria-hidden="true" class="octicon octicon-star" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path fill-rule="evenodd" d="M14 6l-4.9-.64L7 1 4.9 5.36 0 6l3.6 3.26L2.67 14 7 11.67 11.33 14l-.93-4.74z"/></svg>
        Unstar
      </button>
        <a class="social-count js-social-count" href="/aui/art-template/stargazers"
           aria-label="6079 users starred this repository">
          6,079
        </a>
</form>
    <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/aui/art-template/star" class="unstarred js-social-form" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="RbAWYfMQ71sV78Rqf9Js6SUGVTKNS0OP9NyJ1YxXabelsljfadkBIKtU5gJmRUBIvvGiJdnF9jI1b7kRimBoHQ==" /></div>
      <input type="hidden" name="context" value="repository"></input>
      <button
        type="submit"
        class="btn btn-sm btn-with-count js-toggler-target"
        aria-label="Star this repository" title="Star aui/art-template"
        data-ga-click="Repository, click star button, action:blob#show; text:Star">
        <svg aria-hidden="true" class="octicon octicon-star" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path fill-rule="evenodd" d="M14 6l-4.9-.64L7 1 4.9 5.36 0 6l3.6 3.26L2.67 14 7 11.67 11.33 14l-.93-4.74z"/></svg>
        Star
      </button>
        <a class="social-count js-social-count" href="/aui/art-template/stargazers"
           aria-label="6079 users starred this repository">
          6,079
        </a>
</form>  </div>

  </li>

  <li>
          <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/aui/art-template/fork" class="btn-with-count" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="PK3K6e63+CQPPTnG2Du6Q6xva+I+bJChepYyhjIwK4nF9RV3Vlhp0bovTkXeIAOX51fAUWxqxWLTtz+4ZAwGyQ==" /></div>
            <button
                type="submit"
                class="btn btn-sm btn-with-count"
                data-ga-click="Repository, show fork modal, action:blob#show; text:Fork"
                title="Fork your own copy of aui/art-template to your account"
                aria-label="Fork your own copy of aui/art-template to your account">
              <svg aria-hidden="true" class="octicon octicon-repo-forked" height="16" version="1.1" viewBox="0 0 10 16" width="10"><path fill-rule="evenodd" d="M8 1a1.993 1.993 0 0 0-1 3.72V6L5 8 3 6V4.72A1.993 1.993 0 0 0 2 1a1.993 1.993 0 0 0-1 3.72V6.5l3 3v1.78A1.993 1.993 0 0 0 5 15a1.993 1.993 0 0 0 1-3.72V9.5l3-3V4.72A1.993 1.993 0 0 0 8 1zM2 4.2C1.34 4.2.8 3.65.8 3c0-.65.55-1.2 1.2-1.2.65 0 1.2.55 1.2 1.2 0 .65-.55 1.2-1.2 1.2zm3 10c-.66 0-1.2-.55-1.2-1.2 0-.65.55-1.2 1.2-1.2.65 0 1.2.55 1.2 1.2 0 .65-.55 1.2-1.2 1.2zm3-10c-.66 0-1.2-.55-1.2-1.2 0-.65.55-1.2 1.2-1.2.65 0 1.2.55 1.2 1.2 0 .65-.55 1.2-1.2 1.2z"/></svg>
              Fork
            </button>
</form>
    <a href="/aui/art-template/network" class="social-count"
       aria-label="2048 users forked this repository">
      2,048
    </a>
  </li>
</ul>

        <h1 class="public ">
  <svg aria-hidden="true" class="octicon octicon-repo" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M4 9H3V8h1v1zm0-3H3v1h1V6zm0-2H3v1h1V4zm0-2H3v1h1V2zm8-1v12c0 .55-.45 1-1 1H6v2l-1.5-1.5L3 16v-2H1c-.55 0-1-.45-1-1V1c0-.55.45-1 1-1h10c.55 0 1 .45 1 1zm-1 10H1v2h2v-1h3v1h5v-2zm0-10H2v9h9V1z"/></svg>
  <span class="author" itemprop="author"><a href="/aui" class="url fn" rel="author">aui</a></span><!--
--><span class="path-divider">/</span><!--
--><strong itemprop="name"><a href="/aui/art-template" data-pjax="#js-repo-pjax-container">art-template</a></strong>

</h1>

      </div>
      
<nav class="reponav js-repo-nav js-sidenav-container-pjax container"
     itemscope
     itemtype="http://schema.org/BreadcrumbList"
     role="navigation"
     data-pjax="#js-repo-pjax-container">

  <span itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
    <a href="/aui/art-template" class="js-selected-navigation-item selected reponav-item" data-hotkey="g c" data-selected-links="repo_source repo_downloads repo_commits repo_releases repo_tags repo_branches repo_packages /aui/art-template" itemprop="url">
      <svg aria-hidden="true" class="octicon octicon-code" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path fill-rule="evenodd" d="M9.5 3L8 4.5 11.5 8 8 11.5 9.5 13 14 8 9.5 3zm-5 0L0 8l4.5 5L6 11.5 2.5 8 6 4.5 4.5 3z"/></svg>
      <span itemprop="name">Code</span>
      <meta itemprop="position" content="1">
</a>  </span>

    <span itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
      <a href="/aui/art-template/issues" class="js-selected-navigation-item reponav-item" data-hotkey="g i" data-selected-links="repo_issues repo_labels repo_milestones /aui/art-template/issues" itemprop="url">
        <svg aria-hidden="true" class="octicon octicon-issue-opened" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path fill-rule="evenodd" d="M7 2.3c3.14 0 5.7 2.56 5.7 5.7s-2.56 5.7-5.7 5.7A5.71 5.71 0 0 1 1.3 8c0-3.14 2.56-5.7 5.7-5.7zM7 1C3.14 1 0 4.14 0 8s3.14 7 7 7 7-3.14 7-7-3.14-7-7-7zm1 3H6v5h2V4zm0 6H6v2h2v-2z"/></svg>
        <span itemprop="name">Issues</span>
        <span class="Counter">30</span>
        <meta itemprop="position" content="2">
</a>    </span>

  <span itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
    <a href="/aui/art-template/pulls" class="js-selected-navigation-item reponav-item" data-hotkey="g p" data-selected-links="repo_pulls /aui/art-template/pulls" itemprop="url">
      <svg aria-hidden="true" class="octicon octicon-git-pull-request" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M11 11.28V5c-.03-.78-.34-1.47-.94-2.06C9.46 2.35 8.78 2.03 8 2H7V0L4 3l3 3V4h1c.27.02.48.11.69.31.21.2.3.42.31.69v6.28A1.993 1.993 0 0 0 10 15a1.993 1.993 0 0 0 1-3.72zm-1 2.92c-.66 0-1.2-.55-1.2-1.2 0-.65.55-1.2 1.2-1.2.65 0 1.2.55 1.2 1.2 0 .65-.55 1.2-1.2 1.2zM4 3c0-1.11-.89-2-2-2a1.993 1.993 0 0 0-1 3.72v6.56A1.993 1.993 0 0 0 2 15a1.993 1.993 0 0 0 1-3.72V4.72c.59-.34 1-.98 1-1.72zm-.8 10c0 .66-.55 1.2-1.2 1.2-.65 0-1.2-.55-1.2-1.2 0-.65.55-1.2 1.2-1.2.65 0 1.2.55 1.2 1.2zM2 4.2C1.34 4.2.8 3.65.8 3c0-.65.55-1.2 1.2-1.2.65 0 1.2.55 1.2 1.2 0 .65-.55 1.2-1.2 1.2z"/></svg>
      <span itemprop="name">Pull requests</span>
      <span class="Counter">1</span>
      <meta itemprop="position" content="3">
</a>  </span>

    <a href="/aui/art-template/projects" class="js-selected-navigation-item reponav-item" data-hotkey="g b" data-selected-links="repo_projects new_repo_project repo_project /aui/art-template/projects">
      <svg aria-hidden="true" class="octicon octicon-project" height="16" version="1.1" viewBox="0 0 15 16" width="15"><path fill-rule="evenodd" d="M10 12h3V2h-3v10zm-4-2h3V2H6v8zm-4 4h3V2H2v12zm-1 1h13V1H1v14zM14 0H1a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h13a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1z"/></svg>
      Projects
      <span class="Counter" >0</span>
</a>
    <a href="/aui/art-template/wiki" class="js-selected-navigation-item reponav-item" data-hotkey="g w" data-selected-links="repo_wiki /aui/art-template/wiki">
      <svg aria-hidden="true" class="octicon octicon-book" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M3 5h4v1H3V5zm0 3h4V7H3v1zm0 2h4V9H3v1zm11-5h-4v1h4V5zm0 2h-4v1h4V7zm0 2h-4v1h4V9zm2-6v9c0 .55-.45 1-1 1H9.5l-1 1-1-1H2c-.55 0-1-.45-1-1V3c0-.55.45-1 1-1h5.5l1 1 1-1H15c.55 0 1 .45 1 1zm-8 .5L7.5 3H2v9h6V3.5zm7-.5H9.5l-.5.5V12h6V3z"/></svg>
      Wiki
</a>

  <a href="/aui/art-template/pulse" class="js-selected-navigation-item reponav-item" data-selected-links="repo_graphs repo_contributors dependency_graph pulse /aui/art-template/pulse">
    <svg aria-hidden="true" class="octicon octicon-graph" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M16 14v1H0V0h1v14h15zM5 13H3V8h2v5zm4 0H7V3h2v10zm4 0h-2V6h2v7z"/></svg>
    Insights
</a>

</nav>


    </div>

<div class="container new-discussion-timeline experiment-repo-nav">
  <div class="repository-content">

    
  <a href="/aui/art-template/blob/0ba004aaff5e08c7a107fdaf1e4c3204d848f69e/lib/template-web.js" class="d-none js-permalink-shortcut" data-hotkey="y">Permalink</a>

  <!-- blob contrib key: blob_contributors:v21:48b8bcb21e8aa962b6fd1171d1cc7a31 -->

  <div class="file-navigation js-zeroclipboard-container">
    
<div class="select-menu branch-select-menu js-menu-container js-select-menu float-left">
  <button class=" btn btn-sm select-menu-button js-menu-target css-truncate" data-hotkey="w"
    
    type="button" aria-label="Switch branches or tags" aria-expanded="false" aria-haspopup="true">
      <i>Branch:</i>
      <span class="js-select-button css-truncate-target">master</span>
  </button>

  <div class="select-menu-modal-holder js-menu-content js-navigation-container" data-pjax>

    <div class="select-menu-modal">
      <div class="select-menu-header">
        <svg aria-label="Close" class="octicon octicon-x js-menu-close" height="16" role="img" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48z"/></svg>
        <span class="select-menu-title">Switch branches/tags</span>
      </div>

      <div class="select-menu-filters">
        <div class="select-menu-text-filter">
          <input type="text" aria-label="Filter branches/tags" id="context-commitish-filter-field" class="form-control js-filterable-field js-navigation-enable" placeholder="Filter branches/tags">
        </div>
        <div class="select-menu-tabs">
          <ul>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="branches" data-filter-placeholder="Filter branches/tags" class="js-select-menu-tab" role="tab">Branches</a>
            </li>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="tags" data-filter-placeholder="Find a tag…" class="js-select-menu-tab" role="tab">Tags</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="branches" role="menu">

        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/1.4.0/lib/template-web.js"
               data-name="1.4.0"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                1.4.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/2.0.1/lib/template-web.js"
               data-name="2.0.1"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                2.0.1
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/2.0.2/lib/template-web.js"
               data-name="2.0.2"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                2.0.2
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/2.0.3/lib/template-web.js"
               data-name="2.0.3"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                2.0.3
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/2.0.4/lib/template-web.js"
               data-name="2.0.4"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                2.0.4
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/2.1.0(Beta)/lib/template-web.js"
               data-name="2.1.0(Beta)"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                2.1.0(Beta)
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/3.0.0/lib/template-web.js"
               data-name="3.0.0"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                3.0.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/3.1.0/lib/template-web.js"
               data-name="3.1.0"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                3.1.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/4.0.0/lib/template-web.js"
               data-name="4.0.0"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                4.0.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/gh-pages/lib/template-web.js"
               data-name="gh-pages"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                gh-pages
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open selected"
               href="/aui/art-template/blob/master/lib/template-web.js"
               data-name="master"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                master
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
               href="/aui/art-template/blob/v4.12.1/lib/template-web.js"
               data-name="v4.12.1"
               data-skip-pjax="true"
               rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target js-select-menu-filter-text">
                v4.12.1
              </span>
            </a>
        </div>

          <div class="select-menu-no-results">Nothing to show</div>
      </div>

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="tags">
        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.12.1/lib/template-web.js"
              data-name="v4.12.1"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.12.1">
                v4.12.1
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.10.0/lib/template-web.js"
              data-name="v4.10.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.10.0">
                v4.10.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.9.1/lib/template-web.js"
              data-name="v4.9.1"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.9.1">
                v4.9.1
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.9.0/lib/template-web.js"
              data-name="v4.9.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.9.0">
                v4.9.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.8.2/lib/template-web.js"
              data-name="v4.8.2"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.8.2">
                v4.8.2
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.8.1/lib/template-web.js"
              data-name="v4.8.1"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.8.1">
                v4.8.1
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.8.0/lib/template-web.js"
              data-name="v4.8.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.8.0">
                v4.8.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.7.0/lib/template-web.js"
              data-name="v4.7.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.7.0">
                v4.7.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.6.0/lib/template-web.js"
              data-name="v4.6.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.6.0">
                v4.6.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.5.1/lib/template-web.js"
              data-name="v4.5.1"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.5.1">
                v4.5.1
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.5.0/lib/template-web.js"
              data-name="v4.5.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.5.0">
                v4.5.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.4.2/lib/template-web.js"
              data-name="v4.4.2"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.4.2">
                v4.4.2
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.4.1/lib/template-web.js"
              data-name="v4.4.1"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.4.1">
                v4.4.1
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.1.0/lib/template-web.js"
              data-name="v4.1.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.1.0">
                v4.1.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v4.0.0/lib/template-web.js"
              data-name="v4.0.0"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v4.0.0">
                v4.0.0
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/v3.1.3/lib/template-web.js"
              data-name="v3.1.3"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="v3.1.3">
                v3.1.3
              </span>
            </a>
            <a class="select-menu-item js-navigation-item js-navigation-open "
              href="/aui/art-template/tree/3.0.1/lib/template-web.js"
              data-name="3.0.1"
              data-skip-pjax="true"
              rel="nofollow">
              <svg aria-hidden="true" class="octicon octicon-check select-menu-item-icon" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M12 5l-8 8-4-4 1.5-1.5L4 10l6.5-6.5z"/></svg>
              <span class="select-menu-item-text css-truncate-target" title="3.0.1">
                3.0.1
              </span>
            </a>
        </div>

        <div class="select-menu-no-results">Nothing to show</div>
      </div>

    </div>
  </div>
</div>

    <div class="BtnGroup float-right">
      <a href="/aui/art-template/find/master"
            class="js-pjax-capture-input btn btn-sm BtnGroup-item"
            data-pjax
            data-hotkey="t">
        Find file
      </a>
      <button aria-label="Copy file path to clipboard" class="js-zeroclipboard btn btn-sm BtnGroup-item tooltipped tooltipped-s" data-copied-hint="Copied!" type="button">Copy path</button>
    </div>
    <div class="breadcrumb js-zeroclipboard-target">
      <span class="repo-root js-repo-root"><span class="js-path-segment"><a href="/aui/art-template"><span>art-template</span></a></span></span><span class="separator">/</span><span class="js-path-segment"><a href="/aui/art-template/tree/master/lib"><span>lib</span></a></span><span class="separator">/</span><strong class="final-path">template-web.js</strong>
    </div>
  </div>


  
  <div class="commit-tease">
      <span class="float-right">
        <a class="commit-tease-sha" href="/aui/art-template/commit/35fa170523e0dddc6de2a2593e37a08cbfe9ea03" data-pjax>
          35fa170
        </a>
        <relative-time datetime="2017-10-19T08:58:07Z">Oct 19, 2017</relative-time>
      </span>
      <div>
        <img alt="@yinheli" class="avatar" height="20" src="https://avatars1.githubusercontent.com/u/235094?s=40&amp;v=4" width="20" />
        <a href="/yinheli" class="user-mention" rel="contributor">yinheli</a>
          <a href="/aui/art-template/commit/35fa170523e0dddc6de2a2593e37a08cbfe9ea03" class="message" data-pjax="true" title="重新整理 compile block 的代码格式，并增加 nested block 单元测试">重新整理 compile block 的代码格式，并增加 nested block 单元测试</a>
      </div>

    <div class="commit-tease-contributors">
      <button type="button" class="btn-link muted-link contributors-toggle" data-facebox="#blob_contributors_box">
        <strong>2</strong>
         contributors
      </button>
          <a class="avatar-link tooltipped tooltipped-s" aria-label="aui" href="/aui/art-template/commits/master/lib/template-web.js?author=aui"><img alt="@aui" class="avatar" height="20" src="https://avatars3.githubusercontent.com/u/1791748?s=40&amp;v=4" width="20" /> </a>
    <a class="avatar-link tooltipped tooltipped-s" aria-label="yinheli" href="/aui/art-template/commits/master/lib/template-web.js?author=yinheli"><img alt="@yinheli" class="avatar" height="20" src="https://avatars1.githubusercontent.com/u/235094?s=40&amp;v=4" width="20" /> </a>


    </div>

    <div id="blob_contributors_box" style="display:none">
      <h2 class="facebox-header" data-facebox-id="facebox-header">Users who have contributed to this file</h2>
      <ul class="facebox-user-list" data-facebox-id="facebox-description">
          <li class="facebox-user-list-item">
            <img alt="@aui" height="24" src="https://avatars2.githubusercontent.com/u/1791748?s=48&amp;v=4" width="24" />
            <a href="/aui">aui</a>
          </li>
          <li class="facebox-user-list-item">
            <img alt="@yinheli" height="24" src="https://avatars0.githubusercontent.com/u/235094?s=48&amp;v=4" width="24" />
            <a href="/yinheli">yinheli</a>
          </li>
      </ul>
    </div>
  </div>


  <div class="file">
    <div class="file-header">
  <div class="file-actions">

    <div class="BtnGroup">
      <a href="/aui/art-template/raw/master/lib/template-web.js" class="btn btn-sm BtnGroup-item" id="raw-url">Raw</a>
        <a href="/aui/art-template/blame/master/lib/template-web.js" class="btn btn-sm js-update-url-with-hash BtnGroup-item" data-hotkey="b">Blame</a>
      <a href="/aui/art-template/commits/master/lib/template-web.js" class="btn btn-sm BtnGroup-item" rel="nofollow">History</a>
    </div>


        <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/aui/art-template/edit/master/lib/template-web.js" class="inline-form js-update-url-with-hash" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="BLnzxduoJlZkO1i2Mlsuo8b+ENLA6SPmFVg/N9wCs0iipVf9+TjUfyqxPXXnrFmSRNsQ2cA9yoWQOxYXHkb88g==" /></div>
          <button class="btn-octicon tooltipped tooltipped-nw" type="submit"
            aria-label="Fork this project and edit the file" data-hotkey="e" data-disable-with>
            <svg aria-hidden="true" class="octicon octicon-pencil" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path fill-rule="evenodd" d="M0 12v3h3l8-8-3-3-8 8zm3 2H1v-2h1v1h1v1zm10.3-9.3L12 6 9 3l1.3-1.3a.996.996 0 0 1 1.41 0l1.59 1.59c.39.39.39 1.02 0 1.41z"/></svg>
          </button>
</form>        <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="/aui/art-template/delete/master/lib/template-web.js" class="inline-form" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="VwZGx0N0bFSFP+TLptfQuSqNAT4XYBN5G720xhJ6xMRg2LizcyxfapA8AXYoYBnGt0SoLBe1Stmuv/RZes/YqA==" /></div>
          <button class="btn-octicon btn-octicon-danger tooltipped tooltipped-nw" type="submit"
            aria-label="Fork this project and delete the file" data-disable-with>
            <svg aria-hidden="true" class="octicon octicon-trashcan" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M11 2H9c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1H2c-.55 0-1 .45-1 1v1c0 .55.45 1 1 1v9c0 .55.45 1 1 1h7c.55 0 1-.45 1-1V5c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 12H3V5h1v8h1V5h1v8h1V5h1v8h1V5h1v9zm1-10H2V3h9v1z"/></svg>
          </button>
</form>  </div>

  <div class="file-info">
      3 lines (3 sloc)
      <span class="file-info-divider"></span>
    16.2 KB
  </div>
</div>

    

  <div itemprop="text" class="blob-wrapper data type-javascript">
      <table class="highlight tab-size js-file-line-container" data-tab-size="8">
      <tr>
        <td id="L1" class="blob-num js-line-number" data-line-number="1"></td>
        <td id="LC1" class="blob-code blob-code-inner js-file-line">/*! art-template@4.12.2 for browser | https://github.com/aui/art-template */</td>
      </tr>
      <tr>
        <td id="L2" class="blob-num js-line-number" data-line-number="2"></td>
        <td id="LC2" class="blob-code blob-code-inner js-file-line">!function(e,t){&quot;object&quot;==typeof exports&amp;&amp;&quot;object&quot;==typeof module?module.exports=t():&quot;function&quot;==typeof define&amp;&amp;define.amd?define([],t):&quot;object&quot;==typeof exports?exports.template=t():e.template=t()}(this,function(){return function(e){function t(r){if(n[r])return n[r].exports;var i=n[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,t),i.l=!0,i.exports}var n={};return t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&amp;&amp;e.__esModule?function(){return e[&quot;default&quot;]}:function(){return e};return t.d(n,&quot;a&quot;,n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p=&quot;&quot;,t(t.s=6)}([function(e,t,n){(function(t){e.exports=!1;try{e.exports=&quot;[object process]&quot;===Object.prototype.toString.call(t.process)}catch(n){}}).call(t,n(4))},function(e,t,n){&quot;use strict&quot;;var r=n(8),i=n(3),o=n(23),s=function(e,t){t.onerror(e,t);var n=function(){return&quot;{Template Error}&quot;};return n.mappings=[],n.sourcesContent=[],n},a=function c(e){var t=arguments.length&gt;1&amp;&amp;arguments[1]!==undefined?arguments[1]:{};&quot;string&quot;!=typeof e?t=e:t.source=e,t=i.$extend(t),e=t.source,!0===t.debug&amp;&amp;(t.cache=!1,t.minimize=!1,t.compileDebug=!0),t.compileDebug&amp;&amp;(t.minimize=!1),t.filename&amp;&amp;(t.filename=t.resolveFilename(t.filename,t));var n=t.filename,a=t.cache,u=t.caches;if(a&amp;&amp;n){var p=u.get(n);if(p)return p}if(!e)try{e=t.loader(n,t),t.source=e}catch(d){var l=new o({name:&quot;CompileError&quot;,path:n,message:&quot;template not found: &quot;+d.message,stack:d.stack});if(t.bail)throw l;return s(l,t)}var f=void 0,h=new r(t);try{f=h.build()}catch(l){if(l=new o(l),t.bail)throw l;return s(l,t)}var m=function(e,n){try{return f(e,n)}catch(l){if(!t.compileDebug)return t.cache=!1,t.compileDebug=!0,c(t)(e,n);if(l=new o(l),t.bail)throw l;return s(l,t)()}};return m.mappings=f.mappings,m.sourcesContent=f.sourcesContent,m.toString=function(){return f.toString()},a&amp;&amp;n&amp;&amp;u.set(n,m),m};a.Compiler=r,e.exports=a},function(e,t){Object.defineProperty(t,&quot;__esModule&quot;,{value:!0}),t[&quot;default&quot;]=/(([&#39;&quot;])(?:(?!\2|\\).|\\(?:\r\n|[\s\S]))*(\2)?|`(?:[^`\\$]|\\[\s\S]|\$(?!\{)|\$\{(?:[^{}]|\{[^}]*\}?)*\}?)*(`)?)|(\/\/.*)|(\/\*(?:[^*]|\*(?!\/))*(\*\/)?)|(\/(?!\*)(?:\[(?:(?![\]\\]).|\\.)*\]|(?![\/\]\\]).|\\.)+\/(?:(?!\s*(?:\b|[\u0080-\uFFFF$\\&#39;&quot;~({]|[+\-!](?!=)|\.?\d))|[gmiyu]{1,5}\b(?![\u0080-\uFFFF$\\]|\s*(?:[+\-*%&amp;|^&lt;&gt;!=?({]|\/(?![\/*])))))|(0[xX][\da-fA-F]+|0[oO][0-7]+|0[bB][01]+|(?:\d*\.\d+|\d+\.?)(?:[eE][+-]?\d+)?)|((?!\d)(?:(?!\s)[$\w\u0080-\uFFFF]|\\u[\da-fA-F]{4}|\\u\{[\da-fA-F]+\})+)|(--|\+\+|&amp;&amp;|\|\||=&gt;|\.{3}|(?:[+\-\/%&amp;|^]|\*{1,2}|&lt;{1,2}|&gt;{1,3}|!=?|={1,2})=?|[?~.,:;[\](){}])|(\s+)|(^$|[\s\S])/g,t.matchToToken=function(e){var t={type:&quot;invalid&quot;,value:e[0]};return e[1]?(t.type=&quot;string&quot;,t.closed=!(!e[3]&amp;&amp;!e[4])):e[5]?t.type=&quot;comment&quot;:e[6]?(t.type=&quot;comment&quot;,t.closed=!!e[7]):e[8]?t.type=&quot;regex&quot;:e[9]?t.type=&quot;number&quot;:e[10]?t.type=&quot;name&quot;:e[11]?t.type=&quot;punctuator&quot;:e[12]&amp;&amp;(t.type=&quot;whitespace&quot;),t}},function(e,t,n){&quot;use strict&quot;;function r(){this.$extend=function(e){return e=e||{},s(e,e instanceof r?e:this)}}var i=n(0),o=n(12),s=n(13),a=n(14),c=n(15),u=n(16),p=n(17),l=n(18),f=n(19),h=n(20),m=n(22),d={source:null,filename:null,rules:[f,l],escape:!0,debug:!!i&amp;&amp;&quot;production&quot;!==process.env.NODE_ENV,bail:!0,cache:!0,minimize:!0,compileDebug:!1,resolveFilename:m,include:a,htmlMinifier:h,htmlMinifierOptions:{collapseWhitespace:!0,minifyCSS:!0,minifyJS:!0,ignoreCustomFragments:[]},onerror:c,loader:p,caches:u,root:&quot;/&quot;,extname:&quot;.art&quot;,ignore:[],imports:o};r.prototype=d,e.exports=new r},function(e,t){var n;n=function(){return this}();try{n=n||Function(&quot;return this&quot;)()||(0,eval)(&quot;this&quot;)}catch(r){&quot;object&quot;==typeof window&amp;&amp;(n=window)}e.exports=n},function(e,t){},function(e,t,n){&quot;use strict&quot;;var r=n(7),i=n(1),o=n(24),s=function(e,t){return t instanceof Object?r({filename:e},t):i({filename:e,source:t})};s.render=r,s.compile=i,s.defaults=o,e.exports=s},function(e,t,n){&quot;use strict&quot;;var r=n(1),i=function(e,t,n){return r(e,n)(t)};e.exports=i},function(e,t,n){&quot;use strict&quot;;function r(e,t){if(!(e instanceof t))throw new TypeError(&quot;Cannot call a class as a function&quot;)}var i=n(9),o=n(11),s=&quot;$data&quot;,a=&quot;$imports&quot;,c=&quot;print&quot;,u=&quot;include&quot;,p=&quot;extend&quot;,l=&quot;block&quot;,f=&quot;$$out&quot;,h=&quot;$$line&quot;,m=&quot;$$blocks&quot;,d=&quot;$$slice&quot;,v=&quot;$$from&quot;,g=&quot;$$options&quot;,y=function(e,t){return Object.hasOwnProperty.call(e,t)},b=JSON.stringify,x=function(){function e(t){var n,i,y=this;r(this,e);var b=t.source,x=t.minimize,w=t.htmlMinifier;if(this.options=t,this.stacks=[],this.context=[],this.scripts=[],this.CONTEXT_MAP={},this.ignore=[s,a,g].concat(t.ignore),this.internal=(n={},n[f]=&quot;&#39;&#39;&quot;,n[h]=&quot;[0,0]&quot;,n[m]=&quot;arguments[1]||{}&quot;,n[v]=&quot;null&quot;,n[c]=&quot;function(){var s=&#39;&#39;.concat.apply(&#39;&#39;,arguments);&quot;+f+&quot;+=s;return s}&quot;,n[u]=&quot;function(src,data){var s=&quot;+g+&quot;.include(src,data||&quot;+s+&quot;,arguments[2]||&quot;+m+&quot;,&quot;+g+&quot;);&quot;+f+&quot;+=s;return s}&quot;,n[p]=&quot;function(from){&quot;+v+&quot;=from}&quot;,n[d]=&quot;function(c,p,s){p=&quot;+f+&quot;;&quot;+f+&quot;=&#39;&#39;;c();s=&quot;+f+&quot;;&quot;+f+&quot;=p+s;return s}&quot;,n[l]=&quot;function(){var a=arguments,s;if(typeof a[0]===&#39;function&#39;){return &quot;+d+&quot;(a[0])}else if(&quot;+v+&quot;){if(!&quot;+m+&quot;[a[0]]){&quot;+m+&quot;[a[0]]=&quot;+d+&quot;(a[1])}else{&quot;+f+&quot;+=&quot;+m+&quot;[a[0]]}}else{s=&quot;+m+&quot;[a[0]];if(typeof s===&#39;string&#39;){&quot;+f+&quot;+=s}else{s=&quot;+d+&quot;(a[1])}return s}}&quot;,n),this.dependencies=(i={},i[c]=[f],i[u]=[f,g,s,m],i[p]=[v,u],i[l]=[d,v,f,m],i),this.importContext(f),t.compileDebug&amp;&amp;this.importContext(h),x)try{b=w(b,t)}catch(E){}this.source=b,this.getTplTokens(b,t.rules,this).forEach(function(e){e.type===o.TYPE_STRING?y.parseString(e):y.parseExpression(e)})}return e.prototype.getTplTokens=function(){return o.apply(undefined,arguments)},e.prototype.getEsTokens=function(e){return i(e)},e.prototype.getVariables=function(e){var t=!1;return e.filter(function(e){return&quot;whitespace&quot;!==e.type&amp;&amp;&quot;comment&quot;!==e.type}).filter(function(e){return&quot;name&quot;===e.type&amp;&amp;!t||(t=&quot;punctuator&quot;===e.type&amp;&amp;&quot;.&quot;===e.value,!1)}).map(function(e){return e.value})},e.prototype.importContext=function(e){var t=this,n=&quot;&quot;,r=this.internal,i=this.dependencies,o=this.ignore,c=this.context,u=this.options,p=u.imports,l=this.CONTEXT_MAP;y(l,e)||-1!==o.indexOf(e)||(y(r,e)?(n=r[e],y(i,e)&amp;&amp;i[e].forEach(function(e){return t.importContext(e)})):n=&quot;$escape&quot;===e||&quot;$each&quot;===e||y(p,e)?a+&quot;.&quot;+e:s+&quot;.&quot;+e,l[e]=n,c.push({name:e,value:n}))},e.prototype.parseString=function(e){var t=e.value;if(t){var n=f+&quot;+=&quot;+b(t);this.scripts.push({source:t,tplToken:e,code:n})}},e.prototype.parseExpression=function(e){var t=this,n=e.value,r=e.script,i=r.output,s=this.options.escape,a=r.code;i&amp;&amp;(a=!1===s||i===o.TYPE_RAW?f+&quot;+=&quot;+r.code:f+&quot;+=$escape(&quot;+r.code+&quot;)&quot;);var c=this.getEsTokens(a);this.getVariables(c).forEach(function(e){return t.importContext(e)}),this.scripts.push({source:n,tplToken:e,code:a})},e.prototype.checkExpression=function(e){for(var t=[[/^\s*}[\w\W]*?{?[\s;]*$/,&quot;&quot;],[/(^[\w\W]*?\([\w\W]*?(?:=&gt;|\([\w\W]*?\))\s*{[\s;]*$)/,&quot;$1})&quot;],[/(^[\w\W]*?\([\w\W]*?\)\s*{[\s;]*$)/,&quot;$1}&quot;]],n=0;n&lt;t.length;){if(t[n][0].test(e)){var r;e=(r=e).replace.apply(r,t[n]);break}n++}try{return new Function(e),!0}catch(i){return!1}},e.prototype.build=function(){var e=this.options,t=this.context,n=this.scripts,r=this.stacks,i=this.source,c=e.filename,l=e.imports,d=[],x=y(this.CONTEXT_MAP,p),w=0,E=function(e,t){var n=t.line,i=t.start,o={generated:{line:r.length+w+1,column:1},original:{line:n+1,column:i+1}};return w+=e.split(/\n/).length-1,o},k=function(e){return e.replace(/^[\t ]+|[\t ]$/g,&quot;&quot;)};r.push(&quot;function(&quot;+s+&quot;){&quot;),r.push(&quot;&#39;use strict&#39;&quot;),r.push(s+&quot;=&quot;+s+&quot;||{}&quot;),r.push(&quot;var &quot;+t.map(function(e){return e.name+&quot;=&quot;+e.value}).join(&quot;,&quot;)),e.compileDebug?(r.push(&quot;try{&quot;),n.forEach(function(e){e.tplToken.type===o.TYPE_EXPRESSION&amp;&amp;r.push(h+&quot;=[&quot;+[e.tplToken.line,e.tplToken.start].join(&quot;,&quot;)+&quot;]&quot;),d.push(E(e.code,e.tplToken)),r.push(k(e.code))}),r.push(&quot;}catch(error){&quot;),r.push(&quot;throw {&quot;+[&quot;name:&#39;RuntimeError&#39;&quot;,&quot;path:&quot;+b(c),&quot;message:error.message&quot;,&quot;line:&quot;+h+&quot;[0]+1&quot;,&quot;column:&quot;+h+&quot;[1]+1&quot;,&quot;source:&quot;+b(i),&quot;stack:error.stack&quot;].join(&quot;,&quot;)+&quot;}&quot;),r.push(&quot;}&quot;)):n.forEach(function(e){d.push(E(e.code,e.tplToken)),r.push(k(e.code))}),x&amp;&amp;(r.push(f+&quot;=&#39;&#39;&quot;),r.push(u+&quot;(&quot;+v+&quot;,&quot;+s+&quot;,&quot;+m+&quot;)&quot;)),r.push(&quot;return &quot;+f),r.push(&quot;}&quot;);var T=r.join(&quot;\n&quot;);try{var O=new Function(a,g,&quot;return &quot;+T)(l,e);return O.mappings=d,O.sourcesContent=[i],O}catch(F){for(var $=0,j=0,S=0,_=void 0;$&lt;n.length;){var C=n[$];if(!this.checkExpression(C.code)){j=C.tplToken.line,S=C.tplToken.start,_=C.code;break}$++}throw{name:&quot;CompileError&quot;,path:c,message:F.message,line:j+1,column:S+1,source:i,generated:_,stack:F.stack}}},e}();x.CONSTS={DATA:s,IMPORTS:a,PRINT:c,INCLUDE:u,EXTEND:p,BLOCK:l,OPTIONS:g,OUT:f,LINE:h,BLOCKS:m,SLICE:d,FROM:v,ESCAPE:&quot;$escape&quot;,EACH:&quot;$each&quot;},e.exports=x},function(e,t,n){&quot;use strict&quot;;var r=n(10),i=n(2)[&quot;default&quot;],o=n(2).matchToToken,s=function(e){return e.match(i).map(function(e){return i.lastIndex=0,o(i.exec(e))}).map(function(e){return&quot;name&quot;===e.type&amp;&amp;r(e.value)&amp;&amp;(e.type=&quot;keyword&quot;),e})};e.exports=s},function(e,t,n){&quot;use strict&quot;;var r={&quot;abstract&quot;:!0,await:!0,&quot;boolean&quot;:!0,&quot;break&quot;:!0,&quot;byte&quot;:!0,&quot;case&quot;:!0,&quot;catch&quot;:!0,&quot;char&quot;:!0,&quot;class&quot;:!0,&quot;const&quot;:!0,&quot;continue&quot;:!0,&quot;debugger&quot;:!0,&quot;default&quot;:!0,&quot;delete&quot;:!0,&quot;do&quot;:!0,&quot;double&quot;:!0,&quot;else&quot;:!0,&quot;enum&quot;:!0,&quot;export&quot;:!0,&quot;extends&quot;:!0,&quot;false&quot;:!0,&quot;final&quot;:!0,&quot;finally&quot;:!0,&quot;float&quot;:!0,&quot;for&quot;:!0,&quot;function&quot;:!0,&quot;goto&quot;:!0,&quot;if&quot;:!0,&quot;implements&quot;:!0,&quot;import&quot;:!0,&quot;in&quot;:!0,&quot;instanceof&quot;:!0,&quot;int&quot;:!0,&quot;interface&quot;:!0,&quot;let&quot;:!0,&quot;long&quot;:!0,&quot;native&quot;:!0,&quot;new&quot;:!0,&quot;null&quot;:!0,&quot;package&quot;:!0,&quot;private&quot;:!0,&quot;protected&quot;:!0,&quot;public&quot;:!0,&quot;return&quot;:!0,&quot;short&quot;:!0,&quot;static&quot;:!0,&quot;super&quot;:!0,&quot;switch&quot;:!0,&quot;synchronized&quot;:!0,&quot;this&quot;:!0,&quot;throw&quot;:!0,&quot;transient&quot;:!0,&quot;true&quot;:!0,&quot;try&quot;:!0,&quot;typeof&quot;:!0,&quot;var&quot;:!0,&quot;void&quot;:!0,&quot;volatile&quot;:!0,&quot;while&quot;:!0,&quot;with&quot;:!0,&quot;yield&quot;:!0};e.exports=function(e){return r.hasOwnProperty(e)}},function(e,t,n){&quot;use strict&quot;;function r(e,t,n,r){var i=new String(e);return i.line=t,i.start=n,i.end=r,i}var i=function(e,t){for(var n=arguments.length&gt;2&amp;&amp;arguments[2]!==undefined?arguments[2]:{},i=[{type:&quot;string&quot;,value:e,line:0,start:0,end:e.length}],o=0;o&lt;t.length;o++)!function(e){for(var t=e.test.ignoreCase?&quot;ig&quot;:&quot;g&quot;,o=e.test.source+&quot;|^$|[\\w\\W]&quot;,s=new RegExp(o,t),a=0;a&lt;i.length;a++)if(&quot;string&quot;===i[a].type){for(var c=i[a].line,u=i[a].start,p=i[a].end,l=i[a].value.match(s),f=[],h=0;h&lt;l.length;h++){var m=l[h];e.test.lastIndex=0;var d=e.test.exec(m),v=d?&quot;expression&quot;:&quot;string&quot;,g=f[f.length-1],y=g||i[a],b=y.value;u=y.line===c?g?g.end:u:b.length-b.lastIndexOf(&quot;\n&quot;)-1,p=u+m.length;var x={type:v,value:m,line:c,start:u,end:p};if(&quot;string&quot;===v)g&amp;&amp;&quot;string&quot;===g.type?(g.value+=m,g.end+=m.length):f.push(x);else{d[0]=new r(d[0],c,u,p);var w=e.use.apply(n,d);x.script=w,f.push(x)}c+=m.split(/\n/).length-1}i.splice.apply(i,[a,1].concat(f)),a+=f.length-1}}(t[o]);return i};i.TYPE_STRING=&quot;string&quot;,i.TYPE_EXPRESSION=&quot;expression&quot;,i.TYPE_RAW=&quot;raw&quot;,i.TYPE_ESCAPE=&quot;escape&quot;,e.exports=i},function(e,t,n){&quot;use strict&quot;;(function(t){function r(e){return&quot;string&quot;!=typeof e&amp;&amp;(e=e===undefined||null===e?&quot;&quot;:&quot;function&quot;==typeof e?r(e.call(e)):JSON.stringify(e)),e}function i(e){var t=&quot;&quot;+e,n=a.exec(t);if(!n)return e;var r=&quot;&quot;,i=void 0,o=void 0,s=void 0;for(i=n.index,o=0;i&lt;t.length;i++){switch(t.charCodeAt(i)){case 34:s=&quot;&amp;#34;&quot;;break;case 38:s=&quot;&amp;#38;&quot;;break;case 39:s=&quot;&amp;#39;&quot;;break;case 60:s=&quot;&amp;#60;&quot;;break;case 62:s=&quot;&amp;#62;&quot;;break;default:continue}o!==i&amp;&amp;(r+=t.substring(o,i)),o=i+1,r+=s}return o!==i?r+t.substring(o,i):r}/*! art-template@runtime | https://github.com/aui/art-template */</td>
      </tr>
      <tr>
        <td id="L3" class="blob-num js-line-number" data-line-number="3"></td>
        <td id="LC3" class="blob-code blob-code-inner js-file-line">var o=n(0),s=Object.create(o?t:window),a=/[&quot;&amp;&#39;&lt;&gt;]/;s.$escape=function(e){return i(r(e))},s.$each=function(e,t){if(Array.isArray(e))for(var n=0,r=e.length;n&lt;r;n++)t(e[n],n);else for(var i in e)t(e[i],i)},e.exports=s}).call(t,n(4))},function(e,t,n){&quot;use strict&quot;;var r=Object.prototype.toString,i=function(e){return null===e?&quot;Null&quot;:r.call(e).slice(8,-1)},o=function s(e,t){var n=void 0,r=i(e);if(&quot;Object&quot;===r?n=Object.create(t||{}):&quot;Array&quot;===r&amp;&amp;(n=[].concat(t||[])),n){for(var o in e)Object.hasOwnProperty.call(e,o)&amp;&amp;(n[o]=s(e[o],n[o]));return n}return e};e.exports=o},function(e,t,n){&quot;use strict&quot;;var r=function(e,t,r,i){var o=n(1);return i=i.$extend({filename:i.resolveFilename(e,i),bail:!0,source:null}),o(i)(t,r)};e.exports=r},function(e,t,n){&quot;use strict&quot;;var r=function(e){console.error(e.name,e.message)};e.exports=r},function(e,t,n){&quot;use strict&quot;;var r={__data:Object.create(null),set:function(e,t){this.__data[e]=t},get:function(e){return this.__data[e]},reset:function(){this.__data={}}};e.exports=r},function(e,t,n){&quot;use strict&quot;;var r=n(0),i=function(e){if(r){return n(5).readFileSync(e,&quot;utf8&quot;)}var t=document.getElementById(e);return t.value||t.innerHTML};e.exports=i},function(e,t,n){&quot;use strict&quot;;var r={test:/{{([@#]?)[ \t]*(\/?)([\w\W]*?)[ \t]*}}/,use:function(e,t,n,i){var o=this,s=o.options,a=o.getEsTokens(i),c=a.map(function(e){return e.value}),u={},p=void 0,l=!!t&amp;&amp;&quot;raw&quot;,f=n+c.shift(),h=function(t,n){console.warn((s.filename||&quot;anonymous&quot;)+&quot;:&quot;+(e.line+1)+&quot;:&quot;+(e.start+1)+&quot;\nTemplate upgrade: {{&quot;+t+&quot;}} -&gt; {{&quot;+n+&quot;}}&quot;)};switch(&quot;#&quot;===t&amp;&amp;h(&quot;#value&quot;,&quot;@value&quot;),f){case&quot;set&quot;:i=&quot;var &quot;+c.join(&quot;&quot;).trim();break;case&quot;if&quot;:i=&quot;if(&quot;+c.join(&quot;&quot;).trim()+&quot;){&quot;;break;case&quot;else&quot;:var m=c.indexOf(&quot;if&quot;);~m?(c.splice(0,m+1),i=&quot;}else if(&quot;+c.join(&quot;&quot;).trim()+&quot;){&quot;):i=&quot;}else{&quot;;break;case&quot;/if&quot;:i=&quot;}&quot;;break;case&quot;each&quot;:p=r._split(a),p.shift(),&quot;as&quot;===p[1]&amp;&amp;(h(&quot;each object as value index&quot;,&quot;each object value index&quot;),p.splice(1,1));i=&quot;$each(&quot;+(p[0]||&quot;$data&quot;)+&quot;,function(&quot;+(p[1]||&quot;$value&quot;)+&quot;,&quot;+(p[2]||&quot;$index&quot;)+&quot;){&quot;;break;case&quot;/each&quot;:i=&quot;})&quot;;break;case&quot;block&quot;:p=r._split(a),p.shift(),i=&quot;block(&quot;+p.join(&quot;,&quot;).trim()+&quot;,function(){&quot;;break;case&quot;/block&quot;:i=&quot;})&quot;;break;case&quot;echo&quot;:f=&quot;print&quot;,h(&quot;echo value&quot;,&quot;value&quot;);case&quot;print&quot;:case&quot;include&quot;:case&quot;extend&quot;:if(0!==c.join(&quot;&quot;).trim().indexOf(&quot;(&quot;)){p=r._split(a),p.shift(),i=f+&quot;(&quot;+p.join(&quot;,&quot;)+&quot;)&quot;;break}default:if(~c.indexOf(&quot;|&quot;)){var d=a.reduce(function(e,t){var n=t.value,r=t.type;return&quot;|&quot;===n?e.push([]):&quot;whitespace&quot;!==r&amp;&amp;&quot;comment&quot;!==r&amp;&amp;(e.length||e.push([]),&quot;:&quot;===n&amp;&amp;1===e[e.length-1].length?h(&quot;value | filter: argv&quot;,&quot;value | filter argv&quot;):e[e.length-1].push(t)),e},[]).map(function(e){return r._split(e)});i=d.reduce(function(e,t){var n=t.shift();return t.unshift(e),&quot;$imports.&quot;+n+&quot;(&quot;+t.join(&quot;,&quot;)+&quot;)&quot;},d.shift().join(&quot; &quot;).trim())}l=l||&quot;escape&quot;}return u.code=i,u.output=l,u},_split:function(e){e=e.filter(function(e){var t=e.type;return&quot;whitespace&quot;!==t&amp;&amp;&quot;comment&quot;!==t});for(var t=0,n=e.shift(),r=/\]|\)/,i=[[n]];t&lt;e.length;){var o=e[t];&quot;punctuator&quot;===o.type||&quot;punctuator&quot;===n.type&amp;&amp;!r.test(n.value)?i[i.length-1].push(o):i.push([o]),n=o,t++}return i.map(function(e){return e.map(function(e){return e.value}).join(&quot;&quot;)})}};e.exports=r},function(e,t,n){&quot;use strict&quot;;var r={test:/&lt;%(#?)((?:==|=#|[=-])?)[ \t]*([\w\W]*?)[ \t]*(-?)%&gt;/,use:function(e,t,n,r){return n={&quot;-&quot;:&quot;raw&quot;,&quot;=&quot;:&quot;escape&quot;,&quot;&quot;:!1,&quot;==&quot;:&quot;raw&quot;,&quot;=#&quot;:&quot;raw&quot;}[n],t&amp;&amp;(r=&quot;/*&quot;+r+&quot;*/&quot;,n=!1),{code:r,output:n}}};e.exports=r},function(e,t,n){&quot;use strict&quot;;var r=n(0),i=function(e,t){if(r){var i,o=n(21).minify,s=t.htmlMinifierOptions,a=t.rules.map(function(e){return e.test});(i=s.ignoreCustomFragments).push.apply(i,a),e=o(e,s)}return e};e.exports=i},function(e,t){!function(e){e.noop=function(){}}(&quot;object&quot;==typeof e&amp;&amp;&quot;object&quot;==typeof e.exports?e.exports:window)},function(e,t,n){&quot;use strict&quot;;var r=n(0),i=/^\.+\//,o=function(e,t){if(r){var o=n(5),s=t.root,a=t.extname;if(i.test(e)){var c=t.filename,u=!c||e===c,p=u?s:o.dirname(c);e=o.resolve(p,e)}else e=o.resolve(s,e);o.extname(e)||(e+=a)}return e};e.exports=o},function(e,t,n){&quot;use strict&quot;;function r(e,t){if(!(e instanceof t))throw new TypeError(&quot;Cannot call a class as a function&quot;)}function i(e,t){if(!e)throw new ReferenceError(&quot;this hasn&#39;t been initialised - super() hasn&#39;t been called&quot;);return!t||&quot;object&quot;!=typeof t&amp;&amp;&quot;function&quot;!=typeof t?e:t}function o(e,t){if(&quot;function&quot;!=typeof t&amp;&amp;null!==t)throw new TypeError(&quot;Super expression must either be null or a function, not &quot;+typeof t);e.prototype=Object.create(t&amp;&amp;t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&amp;&amp;(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}function s(e){var t=e.name,n=e.source,r=e.path,i=e.line,o=e.column,s=e.generated,a=e.message;if(!n)return a;var c=n.split(/\n/),u=Math.max(i-3,0),p=Math.min(c.length,i+3),l=c.slice(u,p).map(function(e,t){var n=t+u+1;return(n===i?&quot; &gt;&gt; &quot;:&quot;    &quot;)+n+&quot;| &quot;+e}).join(&quot;\n&quot;);return(r||&quot;anonymous&quot;)+&quot;:&quot;+i+&quot;:&quot;+o+&quot;\n&quot;+l+&quot;\n\n&quot;+t+&quot;: &quot;+a+(s?&quot;\n   generated: &quot;+s:&quot;&quot;)}var a=function(e){function t(n){r(this,t);var o=i(this,e.call(this,n.message));return o.name=&quot;TemplateError&quot;,o.message=s(n),Error.captureStackTrace&amp;&amp;Error.captureStackTrace(o,o.constructor),o}return o(t,e),t}(Error);e.exports=a},function(e,t,n){&quot;use strict&quot;;e.exports=n(3)}])});</td>
      </tr>
</table>

  <div class="BlobToolbar position-absolute js-file-line-actions dropdown js-menu-container js-select-menu d-none" aria-hidden="true">
    <button class="btn-octicon ml-0 px-2 p-0 bg-white border border-gray-dark rounded-1 dropdown-toggle js-menu-target" id="js-file-line-action-button" type="button" aria-expanded="false" aria-haspopup="true" aria-label="Inline file action toolbar" aria-controls="inline-file-actions">
      <svg aria-hidden="true" class="octicon octicon-kebab-horizontal" height="16" version="1.1" viewBox="0 0 13 16" width="13"><path fill-rule="evenodd" d="M1.5 9a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
    </button>
    <div class="dropdown-menu-content js-menu-content" id="inline-file-actions">
      <ul class="BlobToolbar-dropdown dropdown-menu dropdown-menu-se mt-2">
        <li><a class="js-zeroclipboard dropdown-item" style="cursor:pointer;" id="js-copy-lines" data-original-text="Copy lines">Copy lines</a></li>
        <li><a class="js-zeroclipboard dropdown-item" id= "js-copy-permalink" style="cursor:pointer;" data-original-text="Copy permalink">Copy permalink</a></li>
        <li><a href="/aui/art-template/blame/0ba004aaff5e08c7a107fdaf1e4c3204d848f69e/lib/template-web.js" class="dropdown-item js-update-url-with-hash" id="js-view-git-blame">View git blame</a></li>
          <li><a href="/aui/art-template/issues/new" class="dropdown-item" id="js-new-issue">Open new issue</a></li>
      </ul>
    </div>
  </div>

  </div>

  </div>

  <button type="button" data-facebox="#jump-to-line" data-facebox-class="linejump" data-hotkey="l" class="d-none">Jump to Line</button>
  <div id="jump-to-line" style="display:none">
    <!-- '"` --><!-- </textarea></xmp> --></option></form><form accept-charset="UTF-8" action="" class="js-jump-to-line-form" method="get"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /></div>
      <input class="form-control linejump-input js-jump-to-line-field" type="text" placeholder="Jump to line&hellip;" aria-label="Jump to line" autofocus>
      <button type="submit" class="btn">Go</button>
</form>  </div>

  </div>
  <div class="modal-backdrop js-touch-events"></div>
</div>

    </div>
  </div>

  </div>

      
<div class="footer container-lg px-3" role="contentinfo">
  <div class="position-relative d-flex flex-justify-between py-6 mt-6 f6 text-gray border-top border-gray-light ">
    <ul class="list-style-none d-flex flex-wrap ">
      <li class="mr-3">&copy; 2017 <span title="0.18834s from unicorn-2931269213-433sn">GitHub</span>, Inc.</li>
        <li class="mr-3"><a href="https://github.com/site/terms" data-ga-click="Footer, go to terms, text:terms">Terms</a></li>
        <li class="mr-3"><a href="https://github.com/site/privacy" data-ga-click="Footer, go to privacy, text:privacy">Privacy</a></li>
        <li class="mr-3"><a href="https://github.com/security" data-ga-click="Footer, go to security, text:security">Security</a></li>
        <li class="mr-3"><a href="https://status.github.com/" data-ga-click="Footer, go to status, text:status">Status</a></li>
        <li><a href="https://help.github.com" data-ga-click="Footer, go to help, text:help">Help</a></li>
    </ul>

    <a href="https://github.com" aria-label="Homepage" class="footer-octicon" title="GitHub">
      <svg aria-hidden="true" class="octicon octicon-mark-github" height="24" version="1.1" viewBox="0 0 16 16" width="24"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
</a>
    <ul class="list-style-none d-flex flex-wrap ">
        <li class="mr-3"><a href="https://github.com/contact" data-ga-click="Footer, go to contact, text:contact">Contact GitHub</a></li>
      <li class="mr-3"><a href="https://developer.github.com" data-ga-click="Footer, go to api, text:api">API</a></li>
      <li class="mr-3"><a href="https://training.github.com" data-ga-click="Footer, go to training, text:training">Training</a></li>
      <li class="mr-3"><a href="https://shop.github.com" data-ga-click="Footer, go to shop, text:shop">Shop</a></li>
        <li class="mr-3"><a href="https://github.com/blog" data-ga-click="Footer, go to blog, text:blog">Blog</a></li>
        <li><a href="https://github.com/about" data-ga-click="Footer, go to about, text:about">About</a></li>

    </ul>
  </div>
</div>



  <div id="ajax-error-message" class="ajax-error-message flash flash-error">
    <svg aria-hidden="true" class="octicon octicon-alert" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M8.865 1.52c-.18-.31-.51-.5-.87-.5s-.69.19-.87.5L.275 13.5c-.18.31-.18.69 0 1 .19.31.52.5.87.5h13.7c.36 0 .69-.19.86-.5.17-.31.18-.69.01-1L8.865 1.52zM8.995 13h-2v-2h2v2zm0-3h-2V6h2v4z"/></svg>
    <button type="button" class="flash-close js-ajax-error-dismiss" aria-label="Dismiss error">
      <svg aria-hidden="true" class="octicon octicon-x" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48z"/></svg>
    </button>
    You can't perform that action at this time.
  </div>


    
    <script crossorigin="anonymous" integrity="sha256-R7c3eUp64zkx0aUKgHD8RyBMQTxCRYqXgUMLHeA4450=" src="https://assets-cdn.github.com/assets/frameworks-47b737794a7ae33931d1a50a8070fc47204c413c42458a9781430b1de038e39d.js"></script>
    
    <script async="async" crossorigin="anonymous" integrity="sha256-2SXbOQbClm5PqyyQtrELszP12o+Heu5lfY4EhUqqPSU=" src="https://assets-cdn.github.com/assets/github-d925db3906c2966e4fab2c90b6b10bb333f5da8f877aee657d8e04854aaa3d25.js"></script>
    
    
    
    
  <div class="js-stale-session-flash stale-session-flash flash flash-warn flash-banner d-none">
    <svg aria-hidden="true" class="octicon octicon-alert" height="16" version="1.1" viewBox="0 0 16 16" width="16"><path fill-rule="evenodd" d="M8.865 1.52c-.18-.31-.51-.5-.87-.5s-.69.19-.87.5L.275 13.5c-.18.31-.18.69 0 1 .19.31.52.5.87.5h13.7c.36 0 .69-.19.86-.5.17-.31.18-.69.01-1L8.865 1.52zM8.995 13h-2v-2h2v2zm0-3h-2V6h2v4z"/></svg>
    <span class="signed-in-tab-flash">You signed in with another tab or window. <a href="">Reload</a> to refresh your session.</span>
    <span class="signed-out-tab-flash">You signed out in another tab or window. <a href="">Reload</a> to refresh your session.</span>
  </div>
  <div class="facebox" id="facebox" style="display:none;">
  <div class="facebox-popup">
    <div class="facebox-content" role="dialog" aria-labelledby="facebox-header" aria-describedby="facebox-description">
    </div>
    <button type="button" class="facebox-close js-facebox-close" aria-label="Close modal">
      <svg aria-hidden="true" class="octicon octicon-x" height="16" version="1.1" viewBox="0 0 12 16" width="12"><path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48z"/></svg>
    </button>
  </div>
</div>


  </body>
</html>

