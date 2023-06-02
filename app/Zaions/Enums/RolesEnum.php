<?php

namespace App\Zaions\Enums;


enum RolesEnum: string
{
  case superAdmin = 'superAdmin';
  case admin = 'admin';
  case broker = 'broker';
  case developer = 'developer';
  case investor = 'investor';
  case simpleUser = 'simpleUser';
}
