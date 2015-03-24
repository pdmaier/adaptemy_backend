<?php
//functional tests for v1/UserController/postLogin service
class UserLoginTest extends TestCase {

        //checks user/login when there is no data sent to the service
        //has to return json format data with specific message
        public function testLoginNoData()
        {
            $this->action('POST', 'V1\UserController@postLogin');
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            $this->assertTrue($responseData->code === 401 && $responseData->message === 'No data sent' ? true : false);
        }
        
        //checks user/login when there is wrong data sent to the service
        //has to return json format data with specific message
        public function testLoginBogusData()
        {
            //incorrect fields passed to login service
            $bogus_data = array(
                'test' => 'test',
            );
            
            $this->client->request('POST', '/api/v1.0/user/login', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 400 && $responseData->message === 'Data not sent correctly' ? true : false);
        }
    
        //correct format for the request, but user not found
        public function testLoginUndefinedUser()
        {
            //correct fields passed to service, but incorrect data
            $bogus_data = array(
                'username' => md5(strtotime('now')),
                'password' => md5(strtotime('now')),
            );
            
            $this->client->request('POST', '/api/v1.0/user/login', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 404 && $responseData->message === 'Not found' ? true : false);
        }
        
        //correct format for the request, but user not found
        public function testLoginWrongPwd()
        {
            //correct fields passed to service, but incorrect data
            $bogus_data = array(
                'username' => 'seed',
                'password' => md5(strtotime('now')),
            );
            
            $this->client->request('POST', '/api/v1.0/user/login', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 403 && $responseData->message === 'Wrong credentials' ? true : false);
        }
        
        //correct format for the request, but user not found
        public function testLoginCorrect()
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
            
            //assert response
            $this->assertTrue($responseData->code === 200 && $responseData->message === 'OK' ? true : false);
        }
}
?>