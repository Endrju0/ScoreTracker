<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

    <head>
        <meta charset="utf-8" />
        <title>Aplikacja bazodanowa</title>
        {block name=resources}
            <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
        {/block}
        <link rel="stylesheet" href="{$conf->app_url}/css/style.css">

        <script type="text/javascript" src="{$conf->app_url}/js/functions.js"></script>
    </head>

    <body>
      {if isset($user->id)}
        <div class="navbar">
            <a class="logo">ScoreTracker</a>
            <a href="{$conf->action_root}leaderboard">Leaderboard</a>
            {if isset($user->role) and $user->role == 'admin'}
              <a href="{$conf->action_root}personList" class="pure-menu-heading pure-menu-link">Lista</a>
            {/if}
            {if isset($user->party_id)}
            <a href="{$conf->action_root}stats">Stats</a>
            {/if}
            <a href="{$conf->action_root}profile">Profile</a>
            <div class="navbar-right">
            {if count($conf->roles)>0}
              <a href="{$conf->action_root}logout">Logout</a>
            {else}
              <a href="{$conf->action_root}loginShow">Login</a>
            {/if}
            </div>
          </div>
        {/if}

        <div id="content">
          {block name=top}
          {/block}
          {block name=messages}
              {if $msgs->isMessage()}
                  <div class="messages bottom-margin">
                      <ul>
                          {foreach $msgs->getMessages() as $msg} {strip}
                                  <li class="msg {if $msg->isError()}error{/if} {if $msg->isWarning()}warning{/if} {if $msg->isInfo()}info{/if}">{$msg->text}</li>
                                  {/strip} {/foreach}
                          </ul>
                      </div>
                  {/if}
          {/block}
          {block name=bottom} {/block}
      </div>
    </body>
</html>
