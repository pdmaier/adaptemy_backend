<?php
//functional tests for v1/UserController/postLogin service
class UserCreateStudentTest extends TestCase {

        //checks user/login when there is no data sent to the service
        //has to return json format data with specific message
        public function testCreateStudentNoData()
        {
            $this->action('POST', 'V1\UserController@postCreatestudent');
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            $this->assertTrue($responseData->code === 401 && $responseData->message === 'No data sent' ? true : false);
        }
        
        //checks user/login when there is wrong data sent to the service
        //has to return json format data with specific message
        public function testCreateStudentBogusData()
        {
            //incorrect fields passed to login service
            $bogus_data = array(
                'test' => 'test',
            );
            
            $this->client->request('POST', '/api/v1.0/user/createstudent', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 400 && $responseData->message === 'Data not sent correctly' ? true : false);
        }
    
        //correct format for the request, but user not found
        public function testCreateStudentWrongCriteria()
        {
            //correct fields passed to service, but incorrect data
            $bogus_data = array(
                'username' => 'bogus',
                'password' => 'bogus',
            );
            
            $this->client->request('POST', '/api/v1.0/user/createstudent', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 400 && $responseData->message === 'Password and/or username doesn\'t meet criteria' ? true : false);
        }
        
        //correct format for the request, but user not found
        public function testCreateStudentCorrect()
        {
            //correct fields passed to service, but incorrect data
            $bogus_data = array(
                'username' => 'student',
                'password' => 'tesTing1',
            );
            
            $this->client->request('POST', '/api/v1.0/user/createstudent', $bogus_data);
            
            // Get the response and decode it
            $jsonResponse = $this->client->getResponse()->getContent();
            $responseData = json_decode($jsonResponse);
            
            //assert response
            $this->assertTrue($responseData->code === 200 && $responseData->message === 'Success in creating user' ? true : false);
        }
}
?>