<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

                //calls the UserTableSeeder and ConnectSettingsSeeder first
		$this->call('UserTableSeeder');
                $this->call('ConnectSettingsSeeder');
                
                //more seeder calls here if needed for testing
                //...
	}

}
