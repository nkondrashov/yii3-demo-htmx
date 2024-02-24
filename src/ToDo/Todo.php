<?php

namespace App\ToDo;

use Yiisoft\Validator\Rule\BooleanValue;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\StringValue;

class Todo
{
    #[StringValue]
    public string $id = '';

    #[StringValue]
    #[Length(min: 5)]
    #[Required]
    public string $note  = '';

    #[BooleanValue]
    public bool $is_complete = false;
}
