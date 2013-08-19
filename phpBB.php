<?php

/**
 * component to work with phpBB forum users
 *
 * @author Veaceslav Rudnev <slava.rudnev@gmail.com>
 *
 * @version 0.1
 *
 * This component can:
 * login
 * logout
 * user_add
 * user_delete
 * change_password
 * user_update
 * loggedin
 *
 * In config in section components:
 * 		...
 * 		'phpBB'=>array(
 * 			'class'=>'ext.phpBB.phpBB',
 * 			'path'=>dirname(dirname(dirname(__FILE__))).'/forum/',
 * 			'php'=>'php',//default
 * 			'user_table'=>'phpbb_users',//default
 * 		),
 * 		...
 *
 * Using:
 * Yii::app()->phpBB->login($user->phpBBLogin,$this->password);
 * ...
 *
 */
Yii::import('ext.phpBB.phpbbClass');

class phpBB extends CApplicationComponent{

	/**
	 * Path to forum
	 * @var string
	 */
	public $path;
	/**
	 * PHP file extentions
	 * @var string
	 */
	public $php = 'php';
	/**
	 * phpBB user data table
	 * @var string
	 */
	public $user_table = 'phpbb_users';
	protected $_phpbb;

	public function init() {
		if (!$this->path) {
			throw new CException("Не указан путь к форуму");
		}
		Yii::import($this->path . '.includes.utf.utf_normalizer');
		 $this->_phpbb = new phpbbClass(Yii::getPathOfAlias($this->path) . DIRECTORY_SEPARATOR, $this->php);
	}

	/**
	 * Login in phpBB
	 * @param string $username
	 * @param string $password
	 * @return string 'FAIL' or 'SUCCESS'
	 */
	public function login($username, $password) {
		return $this->_phpbb->user_login(array(
			"username"=>$username,
			"password"=>$password,
		));
	}

	/**
	 * Logout in phpBB
	 * @return string 'FAIL' or 'SUCCESS'
	 */
	public function logout() {
		return $this->_phpbb->user_logout();
	}

	/**
	 * Add user to phpBB
	 * @param string $username
	 * @param string $password
	 * @param string $email
	 * @param int $group_id
	 * @return string 'FAIL' or 'SUCCESS'
	 */
	public function userAdd($username, $password, $email, $group_id = 2) {
		return $this->_phpbb->user_add(array(
			"username"=>$username,
			"user_password"=>$password,
			"user_email"=>$email,
			"group_id"=>$group_id,
			"user_lang"=>Yii::app()->language,
		));
	}

	/**
	 * Delete phpBB user
	 * @param mixed $user integer userid or string username
	 * @return string 'FAIL' or 'SUCCESS'
	 */
	public function userDelete($user) {
		$phpbb_vars = is_int($user) ? array("user_id"=>$user) : array("username"=>$user);

		return $this->_phpbb->user_delete($phpbb_vars);
	}

	/**
	 * Change user password
	 * @param mixed $user integer userid or string username
	 * @param string $password new password
	 * @return string 'FAIL' or 'SUCCESS'
	 */
	public function changePassword($user, $password) {
		$phpbb_vars = is_int($user) ? array("user_id"=>$user) : array("username"=>$user);

		$phpbb_vars['password'] = $password;

		return $this->_phpbb->user_change_password($phpbb_vars);
	}

	/**
	 * Test if user is logged in phpBB
	 * @return string 'FAIL' or 'SUCCESS'
	 */
	public function loggedin() {
		return $this->_phpbb->user_loggedin();
	}

	/**
	 * Update user information
	 * @param array $$phpbb_vars $user_row = array(
	 * 			"username",
	 * 			"user_password",
	 * 			"user_email",
	 * 			"group_id",
	 * 			"user_timezone",
	 * 			"user_dst",
	 * 			"user_lang",
	 * 			"user_type",
	 * 			"user_actkey",
	 * 			"user_dateformat",
	 * 			"user_style",
	 * 			"user_regdate",
	 * 			"user_colour",
	 * 		);
	 * @return string 'FAIL' or 'SUCCESS'
	 */
	public function user_update($phpbb_vars) {
		return $this->_phpbb->user_update($phpbb_vars);
	}

}