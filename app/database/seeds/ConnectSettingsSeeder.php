<?php
class ConnectSettingsSeeder extends Seeder {

    public function run()
    {
        \DB::table('connect_settings')->delete();

        //creates the needed connect_settings entry in the testing db
        \ConnectSettings::create([
            'id' => 1,
            'access_key' => '4b31986a-7559-4862-a270-8eb2860ecf54',
            'api_user' => 'api_user',
            'api_pwd' => 'yvftad6h',
            'default_pwd' => '56hs7YhiQ',
            'handshake_service_url' => 'http://authoring.adaptemy.com/RealiseIt/Services/RITHandshake.asmx/GetHandshakeKeyForPerson',
            'registration_service_url' => 'http://authoring.adaptemy.com/RealiseIt/Services/RITDataOrgPeople.asmx/People_Create',
        ]);
    }

}