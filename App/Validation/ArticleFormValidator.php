<?php

namespace App\Validation;

use App\Exceptions\FormValidationException;

class ArticleFormValidator{
    private array $data;
private array $errors=[];
    private array $rules;

    public function __construct(array $data, array $rules =[]) //'key' => 'required'
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function passes(){
foreach($this->rules as $key=>$rules){
foreach($rules as $rule){
    //"required"
    //"min:3"
    [$name,$attribute]=explode(":",$rule);

    $ruleName ="validate".ucfirst($name); // validateRequired;
    //check if method_exists();
    $this->{$ruleName}($key,$attribute);
    }}
    if(count($this->errors)>0){
throw new FormValidationException();
}
    }
    private function validateMin(string $key, int $attribute){
if(strlen($this->data[$key])<$attribute){
    $this->errors[$key][]="{$key} must be at least {$attribute} characters.";
}
    }
private function validateRequired(string $key):void{
//        $this->>data
    if(empty(trim($this->data[$key]))){
        $this->errors[$key][]="{$key} field is required.";
    }
}
    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}