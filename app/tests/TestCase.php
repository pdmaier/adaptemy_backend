<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {
    
        /**
         * Default preparation for each test
         *
         */
        public function setUp()
        {
            parent::setUp();

            //prepare environment for tests
            $this->prepareForTests();
        }
    

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

        //checks if a response has data attached to it
        //used to check if data is passed properly to a view
        protected function getResponseData($response, $key){

            $content = $response->getOriginalContent();

            $content = $content->getData();

            return $content[$key]->all();

        }
        
        /**
         * Migrates the database and set the mailer to 'pretend'.
         * This will cause the tests to run quickly.
         *
         */
        private function prepareForTests()
        {
            /*(needs to be done manually before running phpunit)
            //Artisan::call('migrate:generate');//generate migration files from live db 
            */
            
            Artisan::call('migrate');//create migration files for testing
            Artisan::call('db:seed', ['--env' => 'testing']);//seed testing tables
            Mail::pretend(true);//switch mailer to pretend to not send mails
        }
}
