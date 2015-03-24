<?php
class User extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
        protected $primaryKey = 'id_user';
        
        //method to create the relationship to UserSessionToken model
        public function token()
        {
            return $this->hasMany('UserSessionToken', 'id_user');
        }
}
