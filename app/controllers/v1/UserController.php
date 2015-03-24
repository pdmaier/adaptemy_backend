<?php
namespace V1;

class UserController extends \BaseController {
        
        /*
         * creates a user entry in the table based on params
         * returns true | false depending on the result of the db operation
         */
        private function createUser($params)
        {
            $user = \User::where('username', '=', $params['username'])->first();
            if (empty($user))
            {
                //create salt and hash for password
                $pwd_mgr = new \Pwd();
                $user = new \User;
                
                //save attributes
                $user->username = $params['username'];
                $user->password = $pwd_mgr->create_hash($params['password']);
                $user->type = $params['type'];
                
                //save to table and return true 
                if ($user->save())
                {
                    $user->rit_username = 'backend_testing'.$user->id_user;
                    if ($user->save())
                    {
                        $RIT = new \RIT();
                        //get admin token
                        $token = $RIT->RIT_Handshake();

                        if ($token != null)
                        {   //send api call to create user
                            $uid = $RIT->RIT_Register_User($user->id_user, $token);
                            
                            if ($uid != null)
                            {
                                //save user uid
                                $user->rit_uid = $uid;
                                
                                if ($user->save())
                                {
                                    return true;
                                }
                            }
                        }
                    }
                    
                    //if something went wrong and method hasn't returned true, delete the entry
                    $user->delete();
                }
            }
            
            //return false if everything failed
            return false;
        }
        
	/**
	 * Creates a new student user with  $username and $password
         * username can contain alfanumeric, underscore, dash and dot (but only inside the string, not at the end)
         * password must have at least 6 chars, one uppercase and one digit
         */
	public function postCreatestudent()
        {
            $input = \Input::all();
            
            if (!empty($input))
            {
                if (!empty($input['password']) && !empty($input['username']))
                {
                    $username = $input['username'];
                    $password = $input['password'];
                    if (preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password) 
                            && preg_match('/^[a-zA-Z0-9_]+([-.][a-zA-Z0-9_]+)*$/', $username))
                    {
                        $params = array(
                            'username' => $username,
                            'password' => $password,
                            'type' => 2
                        );
                        
                        //(success | fail)
                        if ($this->createUser($params))
                            return \Response::json(array(
                                'message' => 'Success in creating user',
                                'code' => 200
                            ));
                        
                        return \Response::json(array(
                                'message' => 'Failed creating user',
                                'code' => 500
                            ));
                    }
                    else{//regex match failed
                        return \Response::json(array(
                            'message' => 'Password and/or username doesn\'t meet criteria',
                            'code' => 400,
                        ));
                    }
                }
                else{//data not sent properly
                    return \Response::json(array(
                        'message' => 'Data not sent correctly',
                        'code' => 400,
                    ));
                }
            }
            else{//no data sent
                return \Response::json(array(
                    'message' => 'No data sent',
                    'code' => 401,
                ));
            }
	}

        /**
	 * Creates a new teacher user with  $username and $password
         * username must be a valid email format
         * password must have at least 6 chars, one uppercase and one digit
         */
	public function postCreateteacher()
	{
            $input = \Input::all();
            
            if (!empty($input))
            {
                if (!empty($input['password']) && !empty($input['username']))
                {
                    $username = $input['username'];
                    $password = $input['password'];
                    if (preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password) 
                            && preg_match('/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/', $username))
                    {
                        $params = array(
                            'username' => $username,
                            'password' => $password,
                            'type' => 3
                        );
                        
                        //(success | fail)
                        if ($this->createUser($params))
                            return \Response::json(array(
                                'message' => 'Success in creating user',
                                'code' => 200
                            ));
                        
                        return \Response::json(array(
                                'message' => 'Failed creating user',
                                'code' => 500
                            ));
                    }
                    else{//regex match failed
                        return \Response::json(array(
                            'message' => 'Password and/or username doesn\'t meet criteria',
                            'code' => 400,
                        ));
                    }
                }
                else{//data not sent properly
                    return \Response::json(array(
                        'message' => 'Data not sent correctly',
                        'code' => 400,
                    ));
                }
            }
            else{//no data sent
                return \Response::json(array(
                    'message' => 'No data sent',
                    'code' => 401,
                ));
            }
	}
        
        //service to allow login into the platform
        //if credentials ok, creates a new session token for user and sends it back alongside the user type
        public function postLogin()
        {
            $input = \Input::all();
            
            if (!empty($input))
            {
                if (!empty($input['username']) && !empty($input['password']))
                {
                    //validate user with his password
                    //return token and user type if success
                    //return fail if failed attempt
                    $username = $input['username'];
                    $pwd = $input['password'];

                    $user = \User::where('username', '=', $username)->first();
                    if (!empty($user))
                    {
                        $pwd_mgr = new \Pwd();
                        //validate password
                        if ($pwd_mgr->validate_password($pwd, $user->password))
                        {
                            $token = new \SessionToken();
                            $token_mgr = \SessionToken::createSession($user->id_user, $token->create());
                            
                            if ($token_mgr !== null)
                            {
                                return \Response::json(array(
                                    'message' => 'OK',
                                    'code' => 200,
                                    'token' => $token_mgr->token,
                                    'type' => $user->type,
                                ));
                            }
                            
                            return \Response::json(array(
                                'message' => 'Couldn\'t create token',
                                'code' => 500
                            ));
                        }
                        else{
                            return \Response::json(array(
                                'message' => 'Wrong credentials',
                                'code' => 403
                            ));
                        }
                    }
                    else{
                        return \Response::json(array(
                                'message' => 'Not found',
                                'code' => 404,
                        ));
                    }
                }

                return \Response::json(array(
                    'message' => 'Data not sent correctly',
                    'code' => 400,
                ));
            }
            
            return \Response::json(array(
                'message' => 'No data sent',
                'code' => 401
            ));
        }
        
        //delete a user's token from the database and logout
        public function postLogout()
        {
            $input = \Input::all();
            
            if (!empty($input))
            {
                if (!empty($input['token']))
                {
                    $token_mgr = new \SessionToken($input['token']);
                    $token = $token_mgr->validate(); 
                    
                    if (!empty($token))
                    {
                        if ($token->delete())
                            return \Response::json(array(
                                'message' => 'Logout successful',
                                'code' => 200,
                        ));
                        
                        return \Response::json(array(
                                'message' => 'Failed logout',
                                'code' => 500,
                        ));
                    }
                    
                    return \Response::json(array(
                                'message' => 'Not found',
                                'code' => 404,
                        ));
                }
                
                return \Response::json(array(
                    'message' => 'Data not sent correctly',
                    'code' => 400,
                ));
            }
            
            return \Response::json(array(
                'message' => 'No data sent',
                'code' => 401
            ));
        }
        
        //returns the user's profile based on the sent token
        public function postProfile()
        {
            $input = \Input::all();
            
            if (!empty($input))
            {
                if (!empty($input['token']))
                {
                    $token_mgr = new \SessionToken($input['token']);
                    $token = $token_mgr->validate(); 
                    
                    if (!empty($token))
                    {
                        return \Response::json(array(
                                'message' => 'OK',
                                'code' => 200,
                                'username' => $token->user->username,
                                'type' => $token->user->type,
                                'some other things in here' => array()
                        ));
                    }
                    
                    return \Response::json(array(
                                'message' => 'Not found',
                                'code' => 404,
                        ));
                }
                
                return \Response::json(array(
                    'message' => 'Data not sent correctly',
                    'code' => 400,
                ));
            }
            
            return \Response::json(array(
                'message' => 'No data sent',
                'code' => 401
            ));
        }


}
