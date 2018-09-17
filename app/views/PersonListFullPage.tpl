{extends file="main.tpl"}

{block name=top}

    <div class="bottom-margin">
        <form id="search-form" class="pure-form pure-form-stacked" onsubmit="ajaxPostForm('search-form', '{$conf->action_root}personListPart', 'table');
                return false;">
            <legend>Search options</legend>
            <fieldset>
                <input type="text" placeholder="login" name="sf_login" value="{$searchForm->login}" /><br />
                <button type="submit" class="pure-button pure-button-primary">Filter</button>
            </fieldset>
        </form>
    </div>

{/block}

{block name=bottom}

    <div class="bottom-margin">
        <a class="pure-button button-success" href="{$conf->action_root}personNew">New user</a>
    </div>

    <div id="table">
        {include file="PersonListTable.tpl"}
    </div>

{/block}
