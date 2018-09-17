{extends file="main.tpl"}

{block name=bottom}
        <form class="pure-form pure-form-stacked" action="{$conf->action_root}newSeason" method="post">
          <fieldset>
           <div class="pure-u-1 pure-u-md-1-3">
                <label for="newSeasonName">New Season</label>
                <input id="newSeasonName" name="newSeasonName" class="pure-input-1-4" type="text">
            </div>
            <button type="submit" class="pure-button pure-button-primary">Save</button>
          </fieldset>
        </form>
        </br>
        <table class="pure-table pure-table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Set as active</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                {foreach $seasonList as $sl}
                    {strip}
                        <tr>
                            <td>{$sl["date"]}</td>
                            <td>{$sl["name"]}</td>
                            <form action="{$conf->action_root}setActiveSeason" method="post">
                              <td>
                                {if $sl["active"] == 0}
                                <button name="id" value="{$sl['id']}" type="submit" class="button-small pure-button">set</button>
                                {/if}
                              </td>
                            </form>
                            <form action="{$conf->action_root}deleteSeason" method="post">
                              <td>
                                {if $sl["active"] == 0}
                                <button name="id" value="{$sl['id']}" type="submit" class="button-small pure-button">del</button>
                                {/if}
                              </td>
                            </form>
                        </tr>
                    {/strip}
                {/foreach}
            </tbody>
        </table>
{/block}
