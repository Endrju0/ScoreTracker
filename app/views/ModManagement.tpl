{extends file="main.tpl"}

{block name=bottom}
        Party name: {$user->party_id} </br>
        {if $partyUserList != null}
          <table class="pure-table pure-table-bordered">
              <thead>
                  <tr>
                      <th>Login</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  {foreach $partyUserList as $pul}
                      {strip}
                          <tr>
                              <td>{$pul["login"]}</td>
                              <form id="passModForm" action="" method="post">
                                <td>
                                  <button name="id" value="{$pul['id']}" type="submit" class="button-small pure-button" onclick="confirmButton()">mod</button>
                                </td>
                              </form>
                          </tr>
                      {/strip}
                  {/foreach}
              </tbody>
          </table>
        {else}
          You are alone in the party.
        {/if}
        <script>
          function confirmButton() {
              if(confirm("Are you sure to pass leadership?")) {
                document.getElementById("passModForm").action = "{$conf->action_root}passMod";
              }
          }
        </script>
{/block}
