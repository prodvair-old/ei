<?php
namespace console\controllers;
 
use yii\console\Controller;
use console\rbac\UserGroupRule;
 
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        // delete previous /console/rbac/items.php, /console/rbac/rules.php
        $auth->removeAll();
 
        // Permissions
        
        // Simple, based on action{$NAME} permissions
        $index  = $auth->createPermission('index');
        $create = $auth->createPermission('create');
        $update = $auth->createPermission('update');
        $delete = $auth->createPermission('delete');
 
        $auth->add($index);
        $auth->add($create);
        $auth->add($update);
        $auth->add($delete);

        // User

        $ownProfile = new OwnProfileRule();
        $auth->add($ownProfile);

        $viewOwnProfile = $auth->createPermission('viewOwnProfile');
        $viewOwnProfile->ruleName = $ownProfile->name;
        $auth->add($viewOwnProfile);
        
        $updateOwnProfile = $auth->createPermission('updateOwnProfile');
        $updateOwnProfile->ruleName = $ownProfile->name;
        $auth->add($updateOwnProfile);

        $auth->addChild($viewOwnProfile, $view);
        $auth->addChild($updateOwnProfile, $update);
        
        // Report

        $ownReport = new OwnReportRule();
        $auth->add($ownReport);

        $createReport = $auth->createPermission('createReport');

        $viewOwnReport = $auth->createPermission('viewOwnReport');
        $viewOwnReport->ruleName = $ownReport->name;
        $auth->add($viewOwnReport);

        $updateOwnReport = $auth->createPermission('updateOwnReport');
        $updateOwnReport->ruleName = $ownReport->name;
        $auth->add($updateOwnReport);

        $deleteOwnReport = $auth->createPermission('deleteOwnReport');
        $deleteOwnReport->ruleName = $ownReport->name;
        $auth->add($deleteOwnReport);

        $auth->addChild($viewOwnReport, $view);
        $auth->addChild($updateOwnReport, $update);
        $auth->addChild($deleteOwnReport, $delete);

        $indexReport = $auth->createPermission('indexReport');

        $auth->addChild($createReport, $create);
        $auth->addChild($indexReport, $index);

        // Torg

        $ownTorg = new OwnTorgRule();
        $auth->add($ownTorg);

        $createTorg = $auth->createPermission('createTorg');
        $viewTorg = $auth->createPermission('viewTorg');
        $updateTorg = $auth->createPermission('updateTorg');

        $viewOwnTorg = $auth->createPermission('viewOwnTorg');
        $viewOwnTorg->ruleName = $ownTorg->name;
        $auth->add($viewOwnTorg);

        $updateOwnTorg = $auth->createPermission('updateOwnTorg');
        $updateOwnTorg->ruleName = $ownTorg->name;
        $auth->add($updateOwnTorg);

        $deleteOwnTorg = $auth->createPermission('deleteOwnTorg');
        $deleteOwnTorg->ruleName = $ownTorg->name;
        $auth->add($deleteOwnTorg);

        $auth->addChild($viewOwnTorg, $view);
        $auth->addChild($updateOwnTorg, $update);
        $auth->addChild($deleteOwnTorg, $delete);

        $indexTorg = $auth->createPermission('indexTorg');

        $auth->addChild($createTorg, $create);
        $auth->addChild($viewTorg, $view);
        $auth->addChild($updateTorg, $update);
        $auth->addChild($indexTorg, $index);

        // Lot

        $ownLot = new OwnLotRule();
        $auth->add($ownLot);

        $createLot = $auth->createPermission('createLot');
        $viewLot = $auth->createPermission('viewLot');
        $updateLot = $auth->createPermission('updateLot');

        $viewOwnLot = $auth->createPermission('viewOwnLot');
        $viewOwnLot->ruleName = $ownLot->name;
        $auth->add($viewOwnLot);

        $updateOwnLot = $auth->createPermission('updateOwnLot');
        $updateOwnLot->ruleName = $ownLot->name;
        $auth->add($updateOwnLot);

        $deleteOwnLot = $auth->createPermission('deleteOwnLot');
        $deleteOwnLot->ruleName = $ownLot->name;
        $auth->add($deleteOwnLot);

        $auth->addChild($viewOwnLot, $view);
        $auth->addChild($updateOwnLot, $update);
        $auth->addChild($deleteOwnLot, $delete);

        $indexLot = $auth->createPermission('indexLot');

        $auth->addChild($createLot, $create);
        $auth->addChild($viewLot, $view);
        $auth->addChild($updateLot, $update);
        $auth->addChild($indexLot, $index);

        // Owner

        $viewOwner = $auth->createPermission('viewOwner');
        $updateOwner = $auth->createPermission('updateOwner');
        $indexOwner = $auth->createPermission('indexOwner');

        $auth->addChild($viewOwner, $view);
        $auth->addChild($updateOwner, $update);
        $auth->addChild($indexOwner, $index);

        // Roles
        
        $role = new UserRoleRule();
        $auth->add($role);

        $user = $auth->createRole('user');
        $user->ruleName  = $role->name;
        $auth->add($user);

        $arbitration_manager = $auth->createRole('arbitrator');
        $arbitration_manager->ruleName  = $role->name;
        $auth->add($arbitration_manager);

        $agent = $auth->createRole('agent');
        $agent->ruleName  = $role->name;
        $auth->add($agent);

        $manager = $auth->createRole('manager');
        $manager->ruleName  = $role->name;
        $auth->add($manager);

        $admin = $auth->createRole('admin');
        $admin->ruleName  = $group->name;
        $auth->add($admin);
 
        // User
        $auth->addChild($user, $viewOwnProfile);
        $auth->addChild($user, $updateOwnProfile);

        $auth->addChild($user, $createReport);
        $auth->addChild($user, $viewOwnReport);
        $auth->addChild($user, $updateOwnReport);
        $auth->addChild($user, $deleteOwnReport);
        $auth->addChild($user, $indexReport);

        // Arbitration manager
        $auth->addChild($arbitrator, $viewOwnTorg);
        $auth->addChild($arbitrator, $updateOwnTorg);
        $auth->addChild($arbitrator, $indexTorg);
        $auth->addChild($arbitrator, $viewOwnLot);
        $auth->addChild($arbitrator, $updateOwnLot);
        $auth->addChild($arbitrator, $indexLot);

        // Agent
        $auth->addChild($user, $createTorg);
        $auth->addChild($user, $deleteOwnTorg);
        $auth->addChild($user, $createLot);
        $auth->addChild($user, $deleteOwnLot);

        // Manager
        $auth->addChild($manager, $viewTorg);
        $auth->addChild($manager, $updateTorg);
        $auth->addChild($manager, $indexTorg);
        $auth->addChild($manager, $viewLot);
        $auth->addChild($manager, $updateLot);
        $auth->addChild($manager, $indexLot);
        $auth->addChild($manager, $viewOwner);
        $auth->addChild($manager, $updateOwner);
        $auth->addChild($manager, $indexOwner);

        // Admin
        $auth->addChild($admin, $create);
        $auth->addChild($admin, $update);
        $auth->addChild($admin, $delete);
        $auth->addChild($admin, $index);

        // Arbitrator can all that can User
        $auth->addChild($arbitrator, $user);
        // Agent can all that can Arbitrator
        $auth->addChild($agent, $arbitrator);
        // Manager can all that can User
        $auth->addChild($manager, $user);
        // Admin can all that can Agent
        $auth->addChild($admin, $agent);
        // Admin can all that can Manager
        $auth->addChild($admin, $manager);
    }
}
