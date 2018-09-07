{extends file="main.tpl"}

{block name=bottom}
    <div id="profile">
      <div id="profile-avatar">
        <img src="{$gravatar}" alt="avatar" class="avatar-shape"/>
      </div>
      <div id="profile-info">
        <table class="pure-table">
          <tr>
            <td>Login:</td>
            <td>{$user->login}</td>
          </tr>
          <tr class="pure-table-odd">
            <td>Role:</td>
            <td>{$user->role}</td>
          </tr>
          <tr>
            <td>Last logged:</td>
            <td>{$user->last_login}</td>
          </tr>
        {if !$isInParty}
            <tr class="pure-table-odd">
              <td colspan="2">Brak party</td>
            </tr>
        {else}
            <tr class="pure-table-odd">
              <td>Party name:</td>
              <td>{$partyName}</td>
            </tr>
            <tr>
              <td colspan="2">
                <form action="{$conf->action_root}leaveParty" method="post" class="register-form">
                    <button type="submit" class="pure-button pure-u-24-24">leave party</button>
                </form>
              </td>
            </tr>
        {/if}
      </table>
      </div>
    </div>

    <div id="gravatar-info-container">
      <div id="gravatar-info-logo">
        <img src="{$conf->app_url}/img/gravatar.svg" alt="Gravatar logo" height="40" width="40">
      </div>
      <div id="gravatar-info-text">
        <b>Gravatar</b></br>
        Avatars are provided by Gravatar. To sign up for a free avatar, please <a href="https://pl.gravatar.com">visit Gravatar</a> now.
      </div>
    </div>
{/block}
