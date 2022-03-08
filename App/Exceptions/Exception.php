<?php
namespace App\Exceptions;

class Exception{

    private array $data;
    private array $errors=[];

    public function __construct($data)
{
    $this->data = $data;
}
public function checkErrors(){
        if(empty($this->data['title'])){
            $this->errors['title']='empty field!';
        }
    if(empty($this->data['description'])){
        $this->errors['description']='empty field!';
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