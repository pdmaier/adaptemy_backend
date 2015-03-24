<?php
namespace V1;

class StudentLearningController extends \BaseController {

    //retrieve all topics available for a user from RIT
    public function postTopiclist()
    {
        $input = \Input::all();
        
        if (!empty($input))
        {
            if (!empty($input['token']))
            {
                $token_mgr = new SessionToken($input['token']);
                $token = $token_mgr->validate();
                
                if (!empty($token))
                {
                    $rit = new RIT();
                    return $rit->RIT_Get_User_Objectives($token->rit_token, null);
                }
                else{
                    return \Response::json(array(
                                'message' => 'Not found',
                                'code' => 404,
                        ));
                }
            }
            else{//data not sent properly
                return \Response::json(array(
                    'message' => 'Data not sent correctly',
                    'code' => 400,
                ));
            }
        }
        else{//no data sent
            return \Response::json(array(
                    'message' => 'No data sent',
                    'code' => 401,
                ));
        }
    }
    
}