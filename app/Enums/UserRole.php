<?php

namespace App\Enums;

// This is a enum class for user roles. It defines two roles: Admin and Customer
enum UserRole: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
}
