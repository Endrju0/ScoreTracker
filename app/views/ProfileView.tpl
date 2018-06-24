{extends file="main.tpl"}

{block name=bottom}
    <img src="{$gravatar}" alt="avatar"/> </br>
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
{/block}
