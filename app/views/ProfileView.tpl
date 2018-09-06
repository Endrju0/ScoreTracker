{extends file="main.tpl"}

{block name=bottom}
    <img src="{$gravatar}" alt="avatar" class="avatar-shape"/> </br>
    Login: {$user->login} </br>
    Role: {$user->role} </br>
    Last logged: {$user->last_login} </br>
    {if !$isInParty}
        Brak party
    {else}
        Party name: {$partyName} </br>
        <form action="{$conf->action_root}leaveParty" method="post" class="register-form">
            <button type="submit">leave party</button>
        </form>
    {/if}
    </br></br>

    <div>
      <div style="float:left; margin-right: 5px;">
        <img src="{$conf->app_url}/img/gravatar.svg" alt="Gravatar logo" height="40" width="40">
      </div>
        <b>Gravatar</b></br>
        Avatars are provided by Gravatar. To sign up for a free avatar, please <a href="https://pl.gravatar.com">visit Gravatar</a> now.
    </div>
{/block}
