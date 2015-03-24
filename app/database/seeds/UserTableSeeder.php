<?php
class UserTableSeeder extends Seeder {

    public function run()
    {
        \DB::table('users')->delete();

        $pwd_mgr = new \Pwd();
        
        \User::create([
            'username' => 'seed',
            'password' => $pwd_mgr->create_hash('seed'),
            'rit_username' => '29may133',
            'rit_uid' => '8c8a20dd-5ddb-45b0-80df-4ba5b434d3d5',
            'type' => 2
        ]);
    }

}