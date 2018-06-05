{extends file="main.tpl"}

{block name=bottom}
{if !$isInParty}
  Brak party
{else}
<form action="{$conf->action_root}leaveParty" method="post" class="register-form">
  <button type="submit">leave party</button>
</form>
{/if}
{/block}
