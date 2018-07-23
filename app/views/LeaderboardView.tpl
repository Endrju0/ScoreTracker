{extends file="main.tpl"} {block name=styles}
    <link rel="stylesheet" href="{$conf->app_url}/css/party_style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous"> {/block} {block name=js}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
{/block}

{block name=bottom}
    {if $partyName != null}
        Party: {$partyName}
        {if $user->role == moderator OR $user->role == admin}
          <div id="modpanel">

          </div>
          <table class="pure-table pure-table-bordered">
              <thead>
                  <tr>
                      <th>Login</th>
                      <th colspan="3">Wins</th>
                      <th colspan="3">Amount</th>
                      <th>Win Ratio</th>
                  </tr>
              </thead>
              <tbody>
                  {foreach $trackerList as $t}
                      {strip}
                          <tr>
                              <td>{$t["login"]}</td>
                              <td>{$t["wins"]}</td>
                              <td><a class="button-small pure-button" href="{$conf->action_url}incWins/{$t['id']}">+</a></td>
                              <td><a class="button-small pure-button" href="{$conf->action_url}decWins/{$t['id']}">-</a></td>
                              <td>{$t["amount"]}</td>
                              <td><a class="button-small pure-button" href="{$conf->action_url}incAmount/{$t['id']}">+</a></td>
                              <td><a class="button-small pure-button" href="{$conf->action_url}decAmount/{$t['id']}">-</a></td>
                              <td>{$t["win_ratio"]}</td>
                          </tr>
                      {/strip}
                  {/foreach}
              </tbody>
          </table>
        {else}
        <table class="pure-table pure-table-bordered">
            <thead>
                <tr>
                    <th>Login</th>
                    <th>Wins</th>
                    <th>Amount</th>
                    <th>Win Ratio</th>
                </tr>
            </thead>
            <tbody>
                {foreach $trackerList as $t}
                    {strip}
                        <tr>
                            <td>{$t["login"]}</td>
                            <td>{$t["wins"]}</td>
                            <td>{$t["amount"]}</td>
                            <td>{$t["win_ratio"]}</td>
                        </tr>
                    {/strip}
                {/foreach}
            </tbody>
        </table>
        {/if}
    {else}
        <div class="login-page">
            <div class="form">
                <form action="{$conf->action_root}createParty" method="post" class="register-form">
                    <p class="message2">You don't have a party! Create one:</p>
                    </br>
                    <input type="text" name="newPartyName" placeholder="party name" />
                    <button type="submit">create</button>
                    <p class="message">Want to join existing one? <a href="#">Join now</a></p>
                </form>
                <form action="{$conf->action_root}joinParty" method="post" class="login-form">
                    <p class="message2">You don't have a party! Join one of these:</p>
                    </br>
                    <input list="parties" name="party">
                    <datalist id="parties">
                        {foreach $partyList as $p}
                            <option value="{$p.name}">
                            {/foreach}
                    </datalist>
                    <button type="submit">join</button>
                    <p class="message">Want your party? <a href="#">Create it</a></p>
                </form>
            </div>
        </div>
        <script type="text/javascript" src="{$conf->app_url}/js/login_functions.js"></script>
    {/if}
{/block}
