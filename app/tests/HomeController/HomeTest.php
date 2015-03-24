<?php
//unit tests associated with root route
class HomeTest extends TestCase {
    
        //checks if home/index route is defined
        public function testRoot()
        {
            $response = $this->action('GET', '\HomeController@getIndex');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
    
        //checks / route
        public function testRootRoute()
        {
            $this->client->request('GET', '/');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks if the / route view has all the required data attached to it
        public function testRootRouteContent()
        {
            $this->client->request('GET', '/');
            $this->assertViewHasAll(array('test'));
        }
        
        //checks if home/index route is defined
        public function testApi()
        {
            $response = $this->action('GET', '\HomeController@getApi');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
    
        //checks / route
        public function testApiRoute()
        {
            $this->client->request('GET', '/api');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks if the / route view has all the required data attached to it
        public function testApiRouteContent()
        {
            $this->client->request('GET', '/api');
            $this->assertViewHasAll(array('test'));
        }
        
        //checks if home/index route is defined
        public function testV1()
        {
            $response = $this->action('GET', '\HomeController@getV1');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
    
        //checks / route
        public function testV1Route()
        {
            $this->client->request('GET', '/api/v1.0');
            $this->assertTrue($this->client->getResponse()->isOk());
        }
        
        //checks if the / route view has all the required data attached to it
        public function testV1RouteContent()
        {
            $this->client->request('GET', '/api/v1.0');
            $this->assertViewHasAll(array('test'));
        }

}
?>