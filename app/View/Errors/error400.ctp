<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>

<div id="login-wrapper" class="container">
	<div class="row">
		<div class="col-xs-3 login-character charGap floatLeft">
			<img src="https://files.focusonthefamily.com/static/oac/img/club/euguen-clipped.png" alt="Eugene" />
		</div>
		<div class="col-xs-8 floatRight">
			<h1>Oops! We can't find that page.</h1>
			<p>404 - Page cannot be found.</p>
			<p>This page may have been moved or deleted. Try one of these links to get back on track:</p>
			<a class="btn btn-lg btn-greenInner btn-form" href="/">Home</a><br />
			<a class="btn btn-lg btn-red signupLink" href="/club#age_check"> Join the Club</a>
			<p><a href="/club#login">Already a member? Sign in!</a></p>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<?php
if (Configure::read('debug') > 0):
	echo $this->element('exception_stack_trace');
endif;
?>
