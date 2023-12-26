<?php

namespace App\Helpers\Enums;

enum AccountTypeEnum: int
{
    case EMAIL_AND_PASSWORD = 0;
    case GOOGLE = 1;
}
