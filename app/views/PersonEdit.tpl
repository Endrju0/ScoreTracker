{extends file="main.tpl"}

{block name=top}

    <div class="bottom-margin">
        <form action="{$conf->action_root}personSave" method="post" class="pure-form pure-form-aligned">
            <fieldset>
                <legend>User data</legend>
                <div class="pure-control-group">
                    <label for="login">Login</label>
                    <input id="login" type="text" placeholder="login" name="login" value="{$form->login}">
                </div>
                <div class="pure-control-group">
                    <label for="password">Password</label>
                    <input id="password" type="text" placeholder="password" name="password">
                </div>
                <div class="pure-control-group">
                    <label for="email">Email</label>
                    <input id="email" type="text" placeholder="email" name="email" value="{$form->email}">
                </div>
                <div class="pure-controls">
                    <input type="submit" class="pure-button pure-button-primary" value="Save"/>
                    <a class="pure-button button-secondary" href="{$conf->action_root}personList">Return</a>
                </div>
            </fieldset>
            <input type="hidden" name="id" value="{$form->id}">
        </form>
    </div>

{/block}
