<?php

//class to create or validate a user's session token in the database
class SessionToken {
    
    private $token;
    
    //constructor
    function __construct($token = null)
    {
        //if passed, update local attribute
        if (!empty($token))
            $this->token = $token;
    }
    
    //creates a new unique session token string
    //returns newly created string
    public function create()
    {
        $unique = 0;
        
        while ($unique == 0)
        {
            $this->token = uniqid() . '_' . md5(mt_rand());

            $session = \UserSessionToken::where('token', '=', $this->token)->first();
            if (empty($session))
                $unique = 1;

            unset($session);
        }

        return $this->token;
    }
    
    //validates a token string against the database table
    //returns UserSessionToken model if found | null if not found
    public function validate()
    {
        $token = \UserSessionToken::where('token', '=', $this->token)->first();
        if (!empty($token))
        {
            //need to check the validity of the RIT token
            //need to create a RIT test service for validating a token
            return $token;
        }
        return null;
    }
    
    public static function createSession($id_user, $token)
    {
        $token_entry = new \UserSessionToken;
        $token_entry->token = $token;
        $token_entry->id_user = $id_user;
        
        $rit = new \RIT();
        $rit_token = $rit->RIT_Handshake_User($id_user);
        if ($rit_token !== null)
        {
            $token_entry->rit_token = $rit_token;
            if ($token_entry->save())
                return $token_entry;
        }
        
        return null;
    }
}