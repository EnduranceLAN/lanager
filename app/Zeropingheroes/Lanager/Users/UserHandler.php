<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\Roles\Role;

class UserHandler {

	public function onStore($user)
	{
		// Make the first user SuperAdmin
		if( count(User::all()) == 1 && ! $user->hasRole('Super Admin') )	$user->roles()->attach(Role::where('name', '=', 'Super Admin')->firstOrFail());

		// Generate an API key if the user does not have one
		if( empty($user->api_key) )
		{
			$user->api_key = md5(str_random(32));
			$user->save();
		}
	}

	/**
	* Register the listeners for the subscriber.
	*
	* @param  Illuminate\Events\Dispatcher  $events
	* @return array
	*/
	public function subscribe($events)
	{
		$events->listen('lanager.users.store', 'Zeropingheroes\Lanager\Users\UserHandler@onStore');
	}

}
