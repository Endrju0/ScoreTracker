{extends file="main.tpl"}
{block name=resources}
    <link rel="stylesheet" href="{$conf->app_url}/css/party_style.css">
    <link rel="stylesheet" href="{$conf->app_url}/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>

    </style>
{/block}

{block name=bottom}
    {if $partyName != null}
        Party: {$partyName}</br>
        <div id="modpanel" class="pure-menu-list custom-restricted-width">
          {if $user->role == moderator OR $user->role == admin}
          <a href="{$conf->action_root}seasonManagement" class="pure-menu pure-menu-link">Season management</a>
          <a href="{$conf->action_root}modManagement" class="pure-menu pure-menu-link">Pass moderator</a>
          {/if}
          <a href="{$conf->action_root}chartAmount" class="pure-menu pure-menu-link">Amount of games chart</a>
          <a href="{$conf->action_root}chartWinRatio" class="pure-menu pure-menu-link">Win ratio chart</a>
        </div>
        {if $user->role == moderator OR $user->role == admin}
          <table class="pure-table pure-table-bordered">
              <thead>
                  <tr>
                      <th>
                        <form action="{$conf->action_root}leaderboard">
                        Login
                        <input type="submit" value="▲">
                        <input type="submit" formaction="{$conf->action_root}sortDescLogin" value="▼">
                      </form>
                    </th>
                    <th colspan="3">
                      <form action="{$conf->action_root}sortAscWins">
                        Wins
                        <input type="submit" value="▲">
                        <input type="submit" formaction="{$conf->action_root}sortDescWins" value="▼">
                      </form>
                    </th>
                      <th colspan="3">
                        <form action="{$conf->action_root}sortAscAmount">
                          Amount
                          <input type="submit" value="▲">
                          <input type="submit" formaction="{$conf->action_root}sortDescAmount" value="▼">
                        </form>
                      </th>
                      <th>
                        <form action="{$conf->action_root}sortAscWr">
                          Win Ratio
                          <input type="submit" value="▲">
                          <input type="submit" formaction="{$conf->action_root}sortDescWr" value="▼">
                        </form>
                      </th>
                  </tr>
              </thead>
              <tbody>
                  {foreach $trackerList as $t}
                      {strip}
                          <tr>
                              <td>
                                <img src="{$t["gravatar"]}" class="avatar-image-cell" alt="avatar"/>
                                {$t["login"]}
                              </td>
                              <td>{$t["wins"]}</td>
                              <form action="{$conf->action_root}incWins" method="post">
                                <td><button name="id" value="{$t['id']}" type="submit" class="button-small pure-button">+</button></td>
                              </form>
                              <form action="{$conf->action_root}decWins" method="post">
                                <input type="hidden" name="validateValue" value="{$t['wins']}" />
                                <td><button name="id" value="{$t['id']}" type="submit" class="button-small pure-button">-</button></td>
                              </form>
                              <td>{$t["amount"]}</td>
                              <form action="{$conf->action_root}incAmount" method="post">
                                <td><button name="id" value="{$t['id']}" type="submit" class="button-small pure-button">+</button></td>
                              </form>
                              <form action="{$conf->action_root}decAmount" method="post">
                                <input type="hidden" name="validateValue" value="{$t['amount']}" />
                                <td><button name="id" value="{$t['id']}" type="submit" class="button-small pure-button">-</button></td>
                              </form>
                              <td>{$t["win_ratio"]}</td>
                          </tr>
                      {/strip}
                      {/foreach}
                      <tr>
                        <form action="{$conf->action_root}addMemberToSeason" method="post">
                          <td colspan="7" id="memberCell">
                            <input list="members" name="memberList" id="memberList">
                            <datalist id="members">
                              {strip}
                                {foreach $selectableUsers as $sl}
                                  <option value="{$sl['login']}">
                                {/foreach}
                              {/strip}
                            </td>
                          </datalist>
                          <td>
                            <button type="submit" class="button-small pure-button">add</button>
                          </td>
                        </form>
                      </tr>
              </tbody>
          </table>
        {else}
        <table class="pure-table pure-table-bordered">
            <thead>
                <tr>
                    <th>
                      <form action="{$conf->action_root}leaderboard">
                      Login
                      <input type="submit" value="▲">
                      <input type="submit" formaction="{$conf->action_root}sortDescLogin" value="▼">
                    </form>
                  </th>
                  <th>
                    <form action="{$conf->action_root}sortAscWins">
                      Wins
                      <input type="submit" value="▲">
                      <input type="submit" formaction="{$conf->action_root}sortDescWins" value="▼">
                    </form>
                  </th>
                    <th>
                      <form action="{$conf->action_root}sortAscAmount">
                        Amount
                        <input type="submit" value="▲">
                        <input type="submit" formaction="{$conf->action_root}sortDescAmount" value="▼">
                      </form>
                    </th>
                    <th>
                      <form action="{$conf->action_root}sortAscWr">
                        Win Ratio
                        <input type="submit" value="▲">
                        <input type="submit" formaction="{$conf->action_root}sortDescWr" value="▼">
                      </form>
                    </th>
                </tr>
            </thead>
            <tbody>
                {foreach $trackerList as $t}
                    {strip}
                        <tr>
                            <td>
                              <img src="{$t["gravatar"]}" class="avatar-image-cell avatar-shape" alt="avatar"/>
                              {$t["login"]}
                            </td>
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
    {/if}
    <script type="text/javascript" src="{$conf->app_url}/js/login_functions.js"></script>
{/block}
