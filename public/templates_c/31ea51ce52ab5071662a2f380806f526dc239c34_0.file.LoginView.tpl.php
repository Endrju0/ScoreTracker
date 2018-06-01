<?php
/* Smarty version 3.1.30, created on 2018-06-01 14:06:43
  from "E:\xampp\htdocs\php_09\app\views\LoginView.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b1136d3d9cc28_81903800',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '31ea51ce52ab5071662a2f380806f526dc239c34' => 
    array (
      0 => 'E:\\xampp\\htdocs\\php_09\\app\\views\\LoginView.tpl',
      1 => 1527854802,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:main.tpl' => 1,
  ),
),false)) {
function content_5b1136d3d9cc28_81903800 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9551675725b1136d3d7f7f7_74889141', 'styles');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13827934915b1136d3d819f1_47873793', 'js');
?>

	
<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10099390865b1136d3d89473_99431070', 'top');
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6261542155b1136d3d9c1d4_56122548', 'messages');
?>

<?php $_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:main.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block 'styles'} */
class Block_9551675725b1136d3d7f7f7_74889141 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/css/login_style.css">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
<?php
}
}
/* {/block 'styles'} */
/* {block 'js'} */
class Block_13827934915b1136d3d819f1_47873793 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/js/login_functions.js"><?php echo '</script'; ?>
>
<?php
}
}
/* {/block 'js'} */
/* {block 'top'} */
class Block_10099390865b1136d3d89473_99431070 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<div class="login-page">
  <div class="form">
    <form class="register-form">
      <input id="id_login" type="text" name="login" value="<?php echo $_smarty_tpl->tpl_vars['form']->value->login;?>
" placeholder="name"/>
      <input id="id_pass" type="password" name="pass" placeholder="password"/>
      <input id="email" type="text" placeholder="email" value="<?php echo $_smarty_tpl->tpl_vars['form']->value->email;?>
" placeholder="email address"/>
      <button>create</button>
      <p class="message">Already registered? <a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->action_root;?>
personNew">Sign In</a></p>
    </form>
    <form action="<?php echo $_smarty_tpl->tpl_vars['conf']->value->action_root;?>
login" method="post" class="login-form">
      <input id="id_login" type="text" name="login" value="<?php echo $_smarty_tpl->tpl_vars['form']->value->login;?>
" placeholder="username"/>
      <input id="id_pass" type="password" name="pass" placeholder="password"/>
      <button type="submit" value="zaloguj">login</button>
      <p class="message">Not registered? <a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->action_root;?>
login">Create an account</a></p>
    </form>

<!--<form action="<?php echo $_smarty_tpl->tpl_vars['conf']->value->action_root;?>
login" method="post" class="pure-form pure-form-aligned bottom-margin">
	<legend>Logowanie do systemu</legend>
	<fieldset>
        <div class="pure-control-group">
			<label for="id_login">login: </label>
			<input id="id_login" type="text" name="login" value="<?php echo $_smarty_tpl->tpl_vars['form']->value->login;?>
"/>
		</div>
        <div class="pure-control-group">
			<label for="id_pass">pass: </label>
			<input id="id_pass" type="password" name="pass" /><br />
		</div>
		<div class="pure-controls">
			<input type="submit" value="zaloguj" class="pure-button pure-button-primary"/>
		</div>
	</fieldset>
</form>-->
<?php
}
}
/* {/block 'top'} */
/* {block 'messages'} */
class Block_6261542155b1136d3d9c1d4_56122548 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php if ($_smarty_tpl->tpl_vars['msgs']->value->isMessage()) {?>
	<div class="messages bottom-margin">
		<ul>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['msgs']->value->getMessages(), 'msg');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['msg']->value) {
?>
		<li class="msg <?php if ($_smarty_tpl->tpl_vars['msg']->value->isError()) {?>error<?php }?> <?php if ($_smarty_tpl->tpl_vars['msg']->value->isWarning()) {?>warning<?php }?> <?php if ($_smarty_tpl->tpl_vars['msg']->value->isInfo()) {?>info<?php }?>"><?php echo $_smarty_tpl->tpl_vars['msg']->value->text;?>
</li>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

		</ul>
	</div>
	<?php }?>
	</div>
</div>
<?php
}
}
/* {/block 'messages'} */
}
