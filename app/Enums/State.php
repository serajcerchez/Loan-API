<?php

namespace App\Enums;

enum State: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case PAID = 2;
}
