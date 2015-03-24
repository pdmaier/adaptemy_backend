<?php
//UserController unit tests
class UserControllerTest extends TestCase {

        //checks if user/login action is defined
        public function testLogin()
        {
            $response = $this->action('POST', 'V1\UserController@postLogin');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
    
        //checks if user/logout action is defined
        public function testLogout()
        {
            $response = $this->action('POST', 'V1\UserController@postLogout');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks if user/createstudent action is defined
        public function testCreateStudent()
        {
            $response = $this->action('POST', 'V1\UserController@postCreatestudent');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks if user/createteacher action is defined
        public function testCreateTeacher()
        {
            $response = $this->action('POST', 'V1\UserController@postCreateteacher');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks if user/profile action is defined
	public function testProfile()
	{
            $response = $this->action('POST', 'V1\UserController@postProfile');
            $this->assertTrue($this->client->getResponse()->isOk());
	}

        //checks user/login route
        public function testLoginRoute()
        {
            $response = $this->call('POST', '/api/v1.0/user/login');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
    
        //checks user/logout route
        public function testLogoutRoute()
        {
            $response = $this->call('POST', '/api/v1.0/user/logout');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks user/createstudent route
        public function testCreateStudentRoute()
        {
            $response = $this->call('POST', '/api/v1.0/user/createstudent');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks user/createteacher route
        public function testCreateTeacherRoute()
        {
            $response = $this->call('POST', '/api/v1.0/user/createteacher');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks user/profile route
	public function testProfileRoute()
	{
            $response = $this->call('POST', '/api/v1.0/user/profile');
            $this->assertTrue($this->client->getResponse()->isOk());
	}
        
}