<?php
class UserSessionToken extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_session_tokens';
        protected $primaryKey = 'id_token';
        
        //method to create the relationship to User model
        public function user()
        {
            return $this->belongsTo('User', 'id_user');
        }
}