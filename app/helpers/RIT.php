<?php
class RIT {
    
    //cURL call to connect to RIT server
    public function RIT_ConnectNoCache($url, $data)
    {
                $curl = curl_init();

                if ($data) {
                        $json = json_encode($data);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                'Cache-Control: no-cache',
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($json))
                        );
                }

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                $resp = curl_exec($curl);
                $resp_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);

                if ($resp_code && $resp_code == 200) {
                        return (object)array(
                                'code' => $resp_code,
                                'status' => 'OK',
                                'message' => '',
                                'json' => $resp,
                                'object' => json_decode($resp)
                        );
                }
                else {
                        return (object)array(
                                'code' => $resp_code,
                                'status' => 'ERROR',
                                'message' => $resp
                        );
                }
    }
    
    //cURL call to connect to RIT server
    public function RIT_Connect($url, $data)
    {
                $curl = curl_init();

                if ($data) {
                        $json = json_encode($data);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($json))
                        );
                }

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                $resp = curl_exec($curl);
                $resp_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);

                if ($resp_code && $resp_code == 200) {
                        return (object)array(
                                'code' => $resp_code,
                                'status' => 'OK',
                                'message' => '',
                                'json' => $resp,
                                'object' => json_decode($resp)
                        );
                }
                else {
                        return (object)array(
                                'code' => $resp_code,
                                'status' => 'ERROR',
                                'message' => $resp
                        );
                }
    }
    
    //connects to RIT server to retrieve a session token for the api_user
    public function RIT_Handshake() 
    {
                $options = \ConnectSettings::find('1');
                
                $url = $options->handshake_service_url;
                $data = array('sAccessId' => $options->access_key,
                                'sUsername' => $options->api_user,
                                'sPassword' => $options->api_pwd
                                );
                

                $resp = $this->RIT_Connect($url, $data);
                //return $resp;
                if ($resp->status == "OK") {
                        return $resp->object->d;//this is the token for the session
                }
                
                return null;
    }
    
    //connects to a RIT server to retrieve a session token for a specific user
    public function RIT_Handshake_User($user_id)
    {
            $options = \ConnectSettings::find('1');
            $user_info = \User::find($user_id);
            $username = $user_info->rit_username;

            $url = $options->handshake_service_url;
            $data = array('sAccessId' => $options->access_key,
                            'sUsername' => $username,
                            'sPassword' => $options->default_pwd
                        );

            $resp = $this->RIT_Connect($url, $data);
            if ($resp->status == "OK") {
                return $resp->object->d;
            }
            else
                return null;
    }
    
    //method to register a new user on RIT (based on the user on the marketing website)
    public function RIT_Register_User($user_id, $token)
    {
            $options = \ConnectSettings::find('1');
            $user_info = \User::find($user_id);
            
            //$token = $this->RIT_Handshake();

            //Register User
            $url = $options->registration_service_url;
            $data = array(
                            'sAccessKey' => $token,
                            'oNewItem' => array(
                                            //issue 57 fix here
                                            'Surname' => $user_info->username,
                                            'Forename1' => $user_info->username,
                                            'Username' => $user_info->rit_username,
                                            'Password' => $options->default_pwd,
                                            'Aspects' => array((object) array(
                                                            'Aspect' => 'eMyData',
                                                            'CanCreateRecords' => true,
                                                            'CanPropogateAspect' => false)
                                                            )
                                            )
                            );
            $resp = $this->RIT_Connect($url, $data);
            //return $resp;
            
            //add user to default organization (clevermaths atm)
            if ($resp->status == "OK") {
                $uid = $resp->object->d->Key;
                $url = $options->add_org_to_pers_service_url;
                $organisation = $options->default_organization;
                
                $data = array(
                                    'sAccessKey' => $token,
                                    'sOrgKey' => $organisation,
                                    'sPersonKey' => $uid,
                                    'sRole' => 'E505680B-C5B4-4426-A050-A703718C0E41',//learner role
                    );
                $resp = $this->RIT_Connect($url, $data);
                return $uid;
            }
            
            return null;
    }
    
    //method to delete a user from RIT
    public function RIT_Delete_User($user_id, $token)
    {
        $options = \ConnectSettings::find('1');
        $user_info = \User::find($user_id);
        
        //url for api service
        $url = $options->delete_user_service;
        
        $data = array(
                    'sAccessKey' => $token,
                    'sPersonKey' => $user_info->rit_uid,
                );
        
        //send the data
        $resp = $this->RIT_Connect($url, $data);
        
        //return response
        return $resp;
    }    
    
    //get objectives for the user
    public function RIT_Get_User_Objectives($token = null, $group_guid = null)
    {
        if($token != null) {
                $options = \ConnectSettings::find('1');
                $url = $options->prod_objectives_service_url;

                $data = array(
                                'sAccessKey' => $token,
                                'sPersonKey' => '',
                                'sProductFilter' => '',
                                'sGroupingFilter' => empty($group_guid) ? '' : $group_guid //filter by grouping if possible
                        );

                $resp = $this->RIT_ConnectNoCache($url, $data);

                if ($resp->status == "OK") 
                    return $resp->json;

                return json_encode($resp->message);
        }
        
        return null;
    }
}
?>