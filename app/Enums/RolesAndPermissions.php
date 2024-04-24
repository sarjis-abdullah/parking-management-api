<?php

namespace App\Enums;


class RolesAndPermissions
{
//#region Roles

    public const EMPLOYEE    = 'employee';
    public const ADMIN = 'admin';
    public const OPERATOR = 'operator';
    public const SUPER_ADMIN = 'super_admin';
    public const CLERK       = 'clerk';

    public const CUSTOMER                   = 'customer';
    public const PRIVATE_CUSTOMER           = 'private_customer';
    public const BUSINESS_CUSTOMER          = 'business_customer';
    public const APPROVED_BUSINESS_CUSTOMER = 'approved_business_customer';
    public const INVESTOR                   = 'investor';
    public const HOME_BUYER                 = 'home_buyer';

//#endregion Roles


//#region Permissions

    public const ALL_PERMISSIONS = '*';

    public const ADMINISTRATION_ACCESS      = 'administration.access';
    public const ADMINISTRATION_PERMISSIONS = [
        self::ADMINISTRATION_ACCESS,
    ];

    public const PROFILE_VIEW        = 'profile.view';
    public const PROFILE_PERMISSIONS = [
        self::PROFILE_VIEW,
    ];

    public const PROJECT_VIEW        = 'project.view';
    public const PROJECT_EDIT        = 'project.edit';
    public const PROJECT_DELETE      = 'project.delete';
    public const PROJECT_ABORT       = 'project.abort';
    public const PROJECT_CREATE      = 'project.create';
    public const PROJECT_PERMISSIONS = [
        self::PROJECT_CREATE,
        self::PROJECT_VIEW,
        self::PROJECT_DELETE,
        self::PROJECT_ABORT,
        self::PROJECT_EDIT,
    ];

    public const USER_VIEW        = 'user.view';
    public const USER_EDIT        = 'user.edit';
    public const USER_DELETE      = 'user.delete';
    public const USER_CREATE      = 'user.create';
    public const USER_PERMISSIONS = [
        self::USER_CREATE,
        self::USER_DELETE,
        self::USER_EDIT,
        self::USER_VIEW,
    ];

    public const ROLE_VIEW        = 'role.view';
    public const ROLE_EDIT        = 'role.edit';
    public const ROLE_DELETE      = 'role.delete';
    public const ROLE_CREATE      = 'role.create';
    public const ROLE_PERMISSIONS = [
        self::ROLE_CREATE,
        self::ROLE_DELETE,
        self::ROLE_EDIT,
        self::ROLE_VIEW,
    ];

    public const TRANSACTION_VIEW        = 'transaction.view';
    public const TRANSACTION_PERMISSIONS = [
        self::TRANSACTION_VIEW,
    ];

    public const INVESTMENT_CLEARANCE          = 'investment.clearance';
    public const INVESTMENT_PERMISSIONS        = [
        self::INVESTMENT_CLEARANCE,
    ];
    public const USER_EDIT_ADMINS              = "users.edit.admins";
    public const CLERK_EDIT_CLERK_DOWN_TO_USER = "clerk.edit.clerk.down.to.user";

//#endregion Permissions


//#region Convenience

    public const EMPLOYEE_ROLES = [
        self::EMPLOYEE,
        self::SUPER_ADMIN,
        self::CLERK,
    ];

    public const CUSTOMER_ROLES = [
        self::CUSTOMER,
        self::APPROVED_BUSINESS_CUSTOMER,
        self::BUSINESS_CUSTOMER,
        self::PRIVATE_CUSTOMER,
        self::INVESTOR,
        self::HOME_BUYER,
    ];

    public const ROLES = [
        self::EMPLOYEE,
        self::SUPER_ADMIN,
        self::CLERK,

        self::CUSTOMER,
        self::APPROVED_BUSINESS_CUSTOMER,
        self::BUSINESS_CUSTOMER,
        self::PRIVATE_CUSTOMER,
        self::INVESTOR,
        self::HOME_BUYER,
    ];

    public const PERMISSIONS = [
        self::ALL_PERMISSIONS,
        ...self::TRANSACTION_PERMISSIONS,
        ...self::PROFILE_PERMISSIONS,
        ...self::PROJECT_PERMISSIONS,
        ...self::ROLE_PERMISSIONS,
        ...self::USER_PERMISSIONS,
        ...self::ADMINISTRATION_PERMISSIONS,
        ...self::INVESTMENT_PERMISSIONS,
    ];

//#endregion Convenience
}
