{extends file="main.tpl"}

{block name=bottom}
        Party name: {$user->party_id} </br>
        <table class="pure-table pure-table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Set as active</th>
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
                        </tr>
                    {/strip}
                {/foreach}
            </tbody>
        </table>
{/block}
