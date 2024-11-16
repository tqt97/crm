<?php

return [
    // guard_name = api
    'api' => [
        'admin' => [
            'ActivityLogController' => [
                'activity_log-list' => ['list'],
            ],
            'MenuController' => [],
            'DepartmentController' => [
                'department-list' => ['index'],
                'department-store' => ['store'],
                'department-show' => ['show'],
                'department-update' => ['update'],
                'department-delete' => ['destroy'],
            ],
            'PermissionController' => [
                'permission-list' => ['index'],
                'permission-store' => ['store'],
                'permission-show' => ['show'],
                'permission-update' => ['update'],
                'permission-delete' => ['destroy'],
            ],
            'RoleController' => [
                'role-list' => ['index'],
                'role-store' => ['store'],
                'role-show' => ['show'],
                'role-update' => ['update'],
                'role-delete' => ['destroy'],
            ],
            'UserController' => [
                'user-list' => ['index'],
                'user-store' => ['store'],
                'user-show' => ['show'],
                'user-update' => ['update'],
                'user-delete' => ['destroy'],
                'user-profile' => ['profile'],
            ],
            'ProductController' => [
                'product-list' => ['index'],
                'product-store' => ['store'],
                'product-show' => ['show'],
                'product-update' => ['update'],
                'product-delete' => ['destroy'],
            ],
            'AssignmentController' => [
                'assignment-list' => ['index'],
                'assignment-store' => ['store'],
                'assignment-show' => ['show'],
                'assignment-update' => ['update'],
                'assignment-delete' => ['destroy'],
            ],
            'CategoryController' => [
                'category-list' => ['index'],
                'category-store' => ['store'],
                'category-show' => ['show'],
                'category-update' => ['update'],
                'category-delete' => ['destroy'],
            ],
            'CategoryAttributeController' => [
                'attribute-list' => ['index'],
                'attribute-store' => ['store'],
                'attribute-show' => ['show'],
                'attribute-update' => ['update'],
                'attribute-delete' => ['destroy'],
            ],
            'AttributeOptionController' => [
                'option-list' => ['index'],
                'option-store' => ['store'],
                'option-show' => ['show'],
                'option-update' => ['update'],
                'option-delete' => ['destroy'],
            ],
            'PositionController' => [
                'position-list' => ['index'],
                'position-store' => ['store'],
                'position-show' => ['show'],
                'position-update' => ['update'],
                'position-delete' => ['destroy'],
            ],
        ],
        'employee' => [
            'MenuController' => [],
            'UserController' => [
                // 'user-show' => ['show'],
                'user-update' => ['update'],
                'user-profile' => ['profile'],
            ],
            'AssignmentController' => [
                'assignment-list' => ['index'],
                'assignment-show' => ['show'],
            ],
        ],
    ],
    // guard_name = web
    'web' => [],
];
