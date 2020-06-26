<?php
return [
    'index' => [
        'type' => 2,
    ],
    'view' => [
        'type' => 2,
    ],
    'create' => [
        'type' => 2,
    ],
    'update' => [
        'type' => 2,
    ],
    'delete' => [
        'type' => 2,
    ],
    'viewUser' => [
        'type' => 2,
        'children' => [
            'view',
        ],
    ],
    'updateUser' => [
        'type' => 2,
        'children' => [
            'update',
        ],
    ],
    'deleteUser' => [
        'type' => 2,
        'children' => [
            'delete',
        ],
    ],
    'indexUser' => [
        'type' => 2,
        'children' => [
            'index',
        ],
    ],
    'viewOwnProfile' => [
        'type' => 2,
        'ruleName' => 'ownProfile',
        'children' => [
            'viewUser',
        ],
    ],
    'updateOwnProfile' => [
        'type' => 2,
        'ruleName' => 'ownProfile',
        'children' => [
            'updateUser',
        ],
    ],
    'createReport' => [
        'type' => 2,
        'children' => [
            'create',
        ],
    ],
    'viewReport' => [
        'type' => 2,
        'children' => [
            'view',
        ],
    ],
    'updateReport' => [
        'type' => 2,
        'children' => [
            'update',
        ],
    ],
    'deleteReport' => [
        'type' => 2,
        'children' => [
            'delete',
        ],
    ],
    'indexReport' => [
        'type' => 2,
        'children' => [
            'index',
        ],
    ],
    'viewOwnReport' => [
        'type' => 2,
        'ruleName' => 'ownReport',
        'children' => [
            'viewReport',
        ],
    ],
    'updateOwnReport' => [
        'type' => 2,
        'ruleName' => 'ownReport',
        'children' => [
            'updateReport',
        ],
    ],
    'deleteOwnReport' => [
        'type' => 2,
        'ruleName' => 'ownReport',
        'children' => [
            'deleteReport',
        ],
    ],
    'createTorg' => [
        'type' => 2,
        'children' => [
            'create',
        ],
    ],
    'viewTorg' => [
        'type' => 2,
        'children' => [
            'view',
        ],
    ],
    'updateTorg' => [
        'type' => 2,
        'children' => [
            'update',
        ],
    ],
    'deleteTorg' => [
        'type' => 2,
        'children' => [
            'delete',
        ],
    ],
    'indexTorg' => [
        'type' => 2,
        'children' => [
            'index',
        ],
    ],
    'viewOwnTorg' => [
        'type' => 2,
        'ruleName' => 'ownTorg',
        'children' => [
            'viewTorg',
        ],
    ],
    'updateOwnTorg' => [
        'type' => 2,
        'ruleName' => 'ownTorg',
        'children' => [
            'updateTorg',
        ],
    ],
    'deleteOwnTorg' => [
        'type' => 2,
        'ruleName' => 'ownTorg',
        'children' => [
            'deleteTorg',
        ],
    ],
    'createLot' => [
        'type' => 2,
        'children' => [
            'create',
        ],
    ],
    'viewLot' => [
        'type' => 2,
        'children' => [
            'view',
        ],
    ],
    'updateLot' => [
        'type' => 2,
        'children' => [
            'update',
        ],
    ],
    'deleteLot' => [
        'type' => 2,
        'children' => [
            'delete',
        ],
    ],
    'indexLot' => [
        'type' => 2,
        'children' => [
            'index',
        ],
    ],
    'viewOwnLot' => [
        'type' => 2,
        'ruleName' => 'ownLot',
        'children' => [
            'viewLot',
        ],
    ],
    'updateOwnLot' => [
        'type' => 2,
        'ruleName' => 'ownLot',
        'children' => [
            'updateLot',
        ],
    ],
    'deleteOwnLot' => [
        'type' => 2,
        'ruleName' => 'ownLot',
        'children' => [
            'deleteLot',
        ],
    ],
    'deleteOrder' => [
        'type' => 2,
        'children' => [
            'delete',
        ],
    ],
    'indexOrder' => [
        'type' => 2,
        'children' => [
            'index',
        ],
    ],
    'deleteOwnOrder' => [
        'type' => 2,
        'ruleName' => 'ownOrder',
        'children' => [
            'deleteOrder',
        ],
    ],
    'createOwner' => [
        'type' => 2,
        'children' => [
            'create',
        ],
    ],
    'viewOwner' => [
        'type' => 2,
        'children' => [
            'view',
        ],
    ],
    'updateOwner' => [
        'type' => 2,
        'children' => [
            'update',
        ],
    ],
    'deleteOwner' => [
        'type' => 2,
        'children' => [
            'delete',
        ],
    ],
    'indexOwner' => [
        'type' => 2,
        'children' => [
            'index',
        ],
    ],
    'user' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'viewOwnProfile',
            'updateOwnProfile',
            'createReport',
            'viewOwnReport',
            'updateOwnReport',
            'deleteOwnReport',
            'indexReport',
            'deleteOwnOrder',
            'indexOrder',
        ],
    ],
    'arbitrator' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'viewOwnTorg',
            'updateOwnTorg',
            'indexTorg',
            'viewOwnLot',
            'updateOwnLot',
            'indexLot',
            'user',
        ],
    ],
    'agent' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'createTorg',
            'viewOwnTorg',
            'updateOwnTorg',
            'deleteOwnTorg',
            'indexTorg',
            'createLot',
            'viewOwnLot',
            'updateOwnLot',
            'deleteOwnLot',
            'indexLot',
            'user',
        ],
    ],
    'manager' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'viewReport',
            'updateReport',
            'deleteReport',
            'viewTorg',
            'updateTorg',
            'indexTorg',
            'viewLot',
            'updateLot',
            'indexLot',
            'viewOwner',
            'updateOwner',
            'indexOwner',
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'viewUser',
            'updateUser',
            'deleteUser',
            'indexUser',
            'createOwner',
            'deleteOwner',
            'deleteTorg',
            'deleteLot',
            'deleteOrder',
            'create',
            'view',
            'update',
            'delete',
            'index',
            'arbitrator',
            'agent',
            'manager',
        ],
    ],
];
