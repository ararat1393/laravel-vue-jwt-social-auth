<?php

namespace App\Validators;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait UserValidator
{
    protected $validator;
    protected $errors;
    protected $scenario;
    protected $rule = [
        'name' => 'required|min:3',
        'surname' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'phone' => 'required|min:7|unique:users',
        'cover_photo' => 'required'
    ];

    public function rules()
    {
        if( $this->scenario == User::SCENARIO_CREATE ){
            $this->rule['password'] = sprintf("required|min:%u",8);
        }
        if( $this->scenario == User::SCENARIO_UPDATE ){
            $this->rule['email'] = sprintf("required|email|%s",Rule::unique('users')->ignore( $this->id ));
            $this->rule['phone'] = sprintf("required|min:7|%s",Rule::unique('users')->ignore( $this->id ));
        }
        return $this->rule;
    }
    /**
     * @param $scenario
     */
    public function setScenario( $scenario )
    {
        $this->scenario = $scenario;
    }

    /**
     * Array keys must be same user keys
     * @param array $array
     * @return User|void
     */
    public function loadAttributes( Array $array = [] )
    {
        $this->attributes = array_filter($array,function ($column){
            return Schema::hasColumn('users',$column ) && !in_array($column,['created_at','updated_at']);
        },ARRAY_FILTER_USE_KEY);
    }
    /**
     * @param array $input
     * @param int $id
     * @return bool
     */
    public function validate()
    {
        $validator = Validator::make($this->attributes, $this->rules());

        if ($validator->passes()) return true;

        $this->errors =  $validator->errors();

        return false;
    }

    /**
     * @param string $coulmn
     * @return mixed
     */
    public function getErrors(string $coulmn = '')
    {
        if( empty($coulmn) ){
            $errors = new \stdClass();
            foreach ($this->errors->getMessages() as $key => $message){
                $errors->$key = $message[0];
            }
            return $errors;
        }
        return $this->errors->get( $coulmn );
    }
}
