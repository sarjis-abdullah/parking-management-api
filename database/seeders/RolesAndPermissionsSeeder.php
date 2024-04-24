<?php



namespace Database\Seeders;


use App\Enums\RolesAndPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        createAllPermissions();

        defineCustomerRole();
        defineRolePrivateCustomer();
        defineRoleBusinessCustomer();
        defineRoleApprovedBusinessCustomer();
        defineRoleInvestor();
        defineRoleHomeBuyer();

        defineEmployee();
        defineRoleClerk();
        defineRoleSuperAdmin();
    }
}


function createAllPermissions()
{
    $createPermission = fn($permission) => Permission::create(['name' => $permission]);

    $sensitive_permissions = [
        RolesAndPermissions::CLERK_EDIT_CLERK_DOWN_TO_USER, RolesAndPermissions::USER_EDIT_ADMINS,
    ];
    collect([...RolesAndPermissions::PERMISSIONS, ...$sensitive_permissions])->each($createPermission);
}

function defineCustomerRole()
{
    Role::create(['name' => RolesAndPermissions::CUSTOMER])
        ->givePermissionTo(RolesAndPermissions::PROFILE_VIEW);
}

function defineRolePrivateCustomer()
{
    Role::create(['name' => RolesAndPermissions::PRIVATE_CUSTOMER])
        ->givePermissionTo([
            RolesAndPermissions::PROFILE_VIEW,
            RolesAndPermissions::INVESTMENT_CLEARANCE,
        ]);
}

function defineRoleBusinessCustomer()
{
    Role::create(['name' => RolesAndPermissions::BUSINESS_CUSTOMER])
        ->givePermissionTo(RolesAndPermissions::PROFILE_VIEW);

}

function defineRoleApprovedBusinessCustomer()
{
    Role::create(['name' => RolesAndPermissions::APPROVED_BUSINESS_CUSTOMER])
        ->givePermissionTo(RolesAndPermissions::INVESTMENT_CLEARANCE);

}

function defineRoleInvestor()
{
    Role::create(['name' => RolesAndPermissions::INVESTOR])
        ->givePermissionTo([
            RolesAndPermissions::PROFILE_VIEW,
            ...RolesAndPermissions::TRANSACTION_PERMISSIONS,
        ]);
}

function defineRoleHomeBuyer()
{
    Role::create(['name' => RolesAndPermissions::OPERATOR])
        ->givePermissionTo([
            RolesAndPermissions::PROFILE_VIEW,
            RolesAndPermissions::PROJECT_VIEW,
            RolesAndPermissions::PROJECT_ABORT,
            ...RolesAndPermissions::TRANSACTION_PERMISSIONS,
        ]);
}

function defineEmployee()
{
    Role::create(['name' => RolesAndPermissions::ADMIN])
        ->givePermissionTo(RolesAndPermissions::PROFILE_VIEW);
}

function defineRoleClerk()
{
    Role::create(['name' => RolesAndPermissions::CLERK])
        ->givePermissionTo([
            ...RolesAndPermissions::PROJECT_PERMISSIONS,
            ...RolesAndPermissions::USER_PERMISSIONS,
            ...RolesAndPermissions::ROLE_PERMISSIONS,
            RolesAndPermissions::TRANSACTION_VIEW,
            RolesAndPermissions::ADMINISTRATION_ACCESS,
            RolesAndPermissions::CLERK_EDIT_CLERK_DOWN_TO_USER,
        ]);
}

function defineRoleSuperAdmin()
{
    Role::create(['name' => RolesAndPermissions::SUPER_ADMIN])
        ->givePermissionTo([
            RolesAndPermissions::ALL_PERMISSIONS,
            ...RolesAndPermissions::PERMISSIONS,
            RolesAndPermissions::USER_EDIT_ADMINS,
        ]);

}

