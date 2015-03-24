<?php
//functional tests for v1/UserController/postLogin service
class UserLogoutTest extends TestCase {

        //checks user/login when there is no data sent to the service
        //has to return json format data with specific message
        public function testLogoutNoData()
        {
            $this->action('POST', 'V1\UserController@postLogout');
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            $this->assertTrue($responseData->code === 401 && $responseData->message === 'No data sent' ? true : false);
        }
        
        //checks user/login when there is wrong data sent to the service
        //has to return json format data with specific message
        public function testLogoutBogusData()
        {
            //incorrect fields passed to login service
            $bogus_data = array(
                'test' => 'test',
            );
            
            $this->client->request('POST', '/api/v1.0/user/logout', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 400 && $responseData->message === 'Data not sent correctly' ? true : false);
        }
    
        //correct format for the request, but user not found
        public function testLogoutUndefinedToken()
        {
            //correct fields passed to service, but incorrect data
            $bogus_data = array(
                'token' => md5(strtotime('now')),
            );
            
            $this->client->request('POST', '/api/v1.0/user/logout', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 404 && $responseData->message === 'Not found' ? true : false);
        }
        
        //correct format for the request, but user not found
        public function testLogoutCorrect()
        {
            //correct fields passed to service, but incorrect data
            $bogus_data = array(
                'username' => 'seed',
                'password' => 'seed',
            );
            
            $this->client->request('POST', '/api/v1.0/user/login', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //retrieve token and send it to user/logout
            if($responseData->code === 200 && $responseData->message === 'OK')
            {
                $token = $responseData->token;
                $data = array(
                    'token' => $token
                );
                
                $this->client->request('POST', '/api/v1.0/user/logout', $data);
            
                // Get the response and decode it
                $jsonResponse2 = $this->client->getResponse()->getContent();
                $responseData2 = json_decode($jsonResponse2);
                
                $this->assertTrue($responseData2->code === 200 && $responseData2->message === 'Logout successful' ? true : false);
            }
            else{
                //response failed for login, test failed
                $this->assertTrue(false);
            }
        }
}
?>