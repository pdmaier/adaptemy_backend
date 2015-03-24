<?php
//functional tests for v1/UserController/postLogin service
class UserCreateTeacherTest extends TestCase {

        //checks user/login when there is no data sent to the service
        //has to return json format data with specific message
        public function testCreateTeacherNoData()
        {
            $this->action('POST', 'V1\UserController@postCreateteacher');
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            $this->assertTrue($responseData->code === 401 && $responseData->message === 'No data sent' ? true : false);
        }
        
        //checks user/login when there is wrong data sent to the service
        //has to return json format data with specific message
        public function testCreateTeacherBogusData()
        {
            //incorrect fields passed to login service
            $bogus_data = array(
                'test' => 'test',
            );
            
            $this->client->request('POST', '/api/v1.0/user/createteacher', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 400 && $responseData->message === 'Data not sent correctly' ? true : false);
        }
    
        //correct format for the request, but user not found
        public function testCreateTeacherWrongCriteria()
        {
            //correct fields passed to service, but incorrect data
            //username needs email, pwd needs at least 6 chars, 1 uppercase
            $bogus_data = array(
                'username' => 'bogus',
                'password' => 'bogus',
            );
            
            $this->client->request('POST', '/api/v1.0/user/createteacher', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 400 && $responseData->message === 'Password and/or username doesn\'t meet criteria' ? true : false);
        }
        
        //correct format for the request, but user not found
        public function testCreateTeacherCorrect()
        {
            //correct fields passed to service, but incorrect data
            $bogus_data = array(
                'username' => 'phpunit.test@test.com',
                'password' => 'tesTing1',
            );
            
            $this->client->request('POST', '/api/v1.0/user/createteacher', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 200 && $responseData->message === 'Success in creating user' ? true : false);
        }
}
?>