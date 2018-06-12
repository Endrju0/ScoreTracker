{extends file="main.tpl"}

{block name=styles}
    <link rel="stylesheet" href="{$conf->app_url}/css/login_style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
{/block}

{block name=js}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
{/block}

{block name=top}

    <div class="login-page">
        <div class="form">
            <form action="{$conf->action_root}register" method="post" class="register-form">
                <input type="text" name="reg_login" placeholder="name"/>
                <input type="password" name="reg_password"  placeholder="password"/>
                <input type="text" name="reg_email" placeholder="email address"/>
                <button type="submit">create</button>
                <p class="message">Already registered? <a href="#">Sign In</a></p>
            </form>
            <form action="{$conf->action_root}login" method="post" class="login-form">
                <input id="id_login" type="text" name="login" value="{$form->login}" placeholder="username"/>
                <input id="id_pass" type="password" name="pass" placeholder="password"/>
                <button type="submit">login</button>
                <p class="message">Not registered? <a href="#">Create an account</a></p>
            </form>
        {/block}

        {block name=messages}
            {if $msgs->isMessage()}
                <div class="messages bottom-margin">
                    <ul>
                        {foreach $msgs->getMessages() as $msg}
                            {strip}
                                <li class="msg {if $msg->isError()}error{/if} {if $msg->isWarning()}warning{/if} {if $msg->isInfo()}info{/if}">{$msg->text}</li>
                                {/strip}
                            {/foreach}
                    </ul>
                </div>
            {/if}
        </div>
    </div>
{/block}

{block name=bottom} <script type="text/javascript" src="{$conf->app_url}/js/login_functions.js"></script> {/block}
