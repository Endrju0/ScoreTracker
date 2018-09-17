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
                <form action="{$conf->action_root}leaveParty" method="post">
                    <button type="submit" class="pure-button pure-u-24-24">leave party</button>
                </form>
              </td>
            </tr>
        {/if}
      </table>
      </div>
    </div>

    <div id="changePasswordForm">
      <form class="pure-form" action="{$conf->action_root}changePassword" method="post">
        <legend>Change password form:</legend>
        <fieldset class="pure-group">
        <input type="password" id="passNew" name="passNew" class="pure-input-1" placeholder="New password" minlength="2" required>
        <input type="password" id="passRetype" class="pure-input-1" placeholder="Confirm new password" minlength="2" required>
        <input type="password" id="passOld" name="passOld" class="pure-input-1" placeholder="Old password" minlength="2" required>
        </fieldset>
        <div id="changePasswordBottomForm">
         <div><input type="checkbox" class="right" onclick="showPasswordFunction()"> Show Password</div>
         <button type="submit" class="pure-button pure-input-10-24 pure-button-primary " onclick="checkIfSamePassword()">Change password</button>
        </div>
      </form>
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

    <script>
      function showPasswordFunction() {
          var passNew = document.getElementById("passNew");
          var passRetype = document.getElementById("passRetype");
          if (passNew.type === "password") {
              passNew.type = "text";
              passRetype.type = "text";
          } else {
              passNew.type = "password";
              passRetype.type = "password";
          }
      }

      function checkIfSamePassword() {
        var passNew = document.getElementById("passNew");
        var passRetype = document.getElementById("passRetype");

        if (passNew.value != passRetype.value) {
          window.alert("Passwords don't match!");
        }
      }
    </script>
{/block}
