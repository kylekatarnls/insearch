<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

User:Eloquent <<< UserInterface, RemindableInterface

	ADMIN = 0x01;
	ADMINISTRATOR = 0x01;
	MODERATOR = 0x02;
	CONTRIBUTOR = 0x04;

	DEFAULT_ADMIN = false;
	DEFAULT_MODERATOR = false;
	DEFAULT_CONTRIBUTOR = false;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	* $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	* $hidden = array('password');

	/**
	 * The attributes fillable by mass assignment.
	 *
	 * @var array
	 */
	* $fillable = array('email', 'password', 'flags');

	/**
	 * Return the method result for Auth::user()
	 *
	 * @return mixed
	 */
	s+ current
		< ($user = Auth::user()) ? $user : new static;

	+ setPasswordAttribute $password
		>attributes['password'] = Hash::make($password);

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	+ getAuthIdentifier
		<>getKey();

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	+ getAuthPassword
		<>password;

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	+ getReminderEmail
		<>email;

	/**
	 * Return true if the all specifed flags are on for the user, false else.
	 *
	 * @return boolean
	 */
	+ haveFlags $flags
		<(>flags & $flags) === $flags;

	/**
	 * Return true if the user is an administrator, false else.
	 *
	 * @return boolean
	 */
	+ isAdmin
		<>haveFlags(:ADMIN);

	/**
	 * Return true if the user is an administrator, false else.
	 *
	 * @return boolean
	 */
	+ isAdministrator
		<>isAdmin();

	/**
	 * Return true if the user is a moderator, false else.
	 *
	 * @return boolean
	 */
	+ isModerator
		<>haveFlags(:MODERATOR);

	/**
	 * Return true if the user is a contributor, false else.
	 *
	 * @return boolean
	 */
	+ isContributor
		<>haveFlags(:CONTRIBUTOR);


	+ getRememberToken
		<>remember_token

	+ setRememberToken $value
		>remember_token = $value

	+ getRememberTokenName
		< 'remember_token'
