<?php
class HomeController extends \BaseController {
    
    //root route
    public function getIndex()
    {
        return View::make('home::index', array(
            'test' => 'test data'
        ));
    }
    
    // /api route - api versions breakdown
    public function getApi()
    {
        return View::make('home::api', array(
            'test' => 'test data'
        ));
    }
    
    // /api/v1.0 route - version 1.0 api services
    public function getV1()
    {
        return View::make('home::v1', array(
            'test' => 'test data'
        ));
    }
}
?>