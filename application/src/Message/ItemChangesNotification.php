<?php
// src/Message/SmsNotification.php
namespace App\Message;

class ItemChangesNotification
{
    public $id;
    public $name;
    public $updatedAt;
    public $method;

    public function __construct($method, $id, $name = "", $updatedAt = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->updatedAt = $updatedAt;
        $this->method = $method;
    }
}
