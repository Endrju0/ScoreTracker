<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

    <head>
        <meta charset="utf-8" />
        <title>Aplikacja bazodanowa</title>
        {block name=styles}
            <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
            <link rel="stylesheet" href="{$conf->app_url}/css/style.css">
        {/block}

        <script type="text/javascript" src="{$conf->app_url}/js/functions.js"></script>
        {block name=js} {/block}
    </head>

    <body style="margin: 20px;">
      {if isset($user->id)}
        <div class="pure-menu pure-menu-horizontal bottom-margin">
            <a href="{$conf->action_root}leaderboard" class="pure-menu-heading pure-menu-link">Leaderboard</a>
            {if isset($user->role) and $user->role == 'admin'}<a href="{$conf->action_root}personList" class="pure-menu-heading pure-menu-link">Lista</a> {/if}
            <a href="{$conf->action_root}profile" class="pure-menu-heading pure-menu-link">Profil</a>
            {if count($conf->roles)>0}
              <a href="{$conf->action_root}logout" class="pure-menu-heading pure-menu-link">Wyloguj</a>
            {else}
              <a href="{$conf->action_root}loginShow" class="pure-menu-heading pure-menu-link">Zaloguj</a>
            {/if}
        {/if}
        </div>

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
    </body>
</html>
