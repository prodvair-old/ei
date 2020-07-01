<?php
namespace console\controllers;
 
use yii\console\Controller;
use console\rbac\UserRoleRule;
use console\rbac\OwnProfileRule;
use console\rbac\OwnReportRule;
use console\rbac\OwnTorgRule;
use console\rbac\OwnLotRule;
use console\rbac\OwnOrderRule;
 
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
        $view   = $auth->createPermission('view');
        $create = $auth->createPermission('create');
        $update = $auth->createPermission('update');
        $delete = $auth->createPermission('delete');
 
        $auth->add($index);
        $auth->add($view);
        $auth->add($create);
        $auth->add($update);
        $auth->add($delete);

        // User

        $viewUser = $auth->createPermission('viewUser');
        $updateUser = $auth->createPermission('updateUser');
        $deleteUser = $auth->createPermission('deleteUser');
        $indexUser = $auth->createPermission('indexUser');

        $auth->add($viewUser);
        $auth->add($updateUser);
        $auth->add($deleteUser);
        $auth->add($indexUser);

        $ownProfile = new OwnProfileRule();
        $auth->add($ownProfile);

        $viewOwnProfile = $auth->createPermission('viewOwnProfile');
        $viewOwnProfile->ruleName = $ownProfile->name;
        $auth->add($viewOwnProfile);
        
        $updateOwnProfile = $auth->createPermission('updateOwnProfile');
        $updateOwnProfile->ruleName = $ownProfile->name;
        $auth->add($updateOwnProfile);

        $auth->addChild($viewOwnProfile, $viewUser);
        $auth->addChild($updateOwnProfile, $updateUser);

        $auth->addChild($viewUser, $view);
        $auth->addChild($updateUser, $update);
        $auth->addChild($deleteUser, $delete);
        $auth->addChild($indexUser, $index);
        
        // Report

        $ownReport = new OwnReportRule();
        $auth->add($ownReport);

        $createReport = $auth->createPermission('createReport');
        $viewReport = $auth->createPermission('viewReport');
        $updateReport = $auth->createPermission('updateReport');
        $deleteReport = $auth->createPermission('deleteReport');
        $indexReport = $auth->createPermission('indexReport');

        $auth->add($createReport);
        $auth->add($viewReport);
        $auth->add($updateReport);
        $auth->add($deleteReport);
        $auth->add($indexReport);

        $viewOwnReport = $auth->createPermission('viewOwnReport');
        $viewOwnReport->ruleName = $ownReport->name;
        $auth->add($viewOwnReport);

        $updateOwnReport = $auth->createPermission('updateOwnReport');
        $updateOwnReport->ruleName = $ownReport->name;
        $auth->add($updateOwnReport);

        $deleteOwnReport = $auth->createPermission('deleteOwnReport');
        $deleteOwnReport->ruleName = $ownReport->name;
        $auth->add($deleteOwnReport);

        $auth->addChild($viewOwnReport, $viewReport);
        $auth->addChild($updateOwnReport, $updateReport);
        $auth->addChild($deleteOwnReport, $deleteReport);

        $auth->addChild($createReport, $create);
        $auth->addChild($viewReport, $view);
        $auth->addChild($updateReport, $update);
        $auth->addChild($deleteReport, $delete);
        $auth->addChild($indexReport, $index);

        // Torg

        $ownTorg = new OwnTorgRule();
        $auth->add($ownTorg);

        $createTorg = $auth->createPermission('createTorg');
        $viewTorg = $auth->createPermission('viewTorg');
        $updateTorg = $auth->createPermission('updateTorg');
        $deleteTorg = $auth->createPermission('deleteTorg');
        $indexTorg = $auth->createPermission('indexTorg');

        $auth->add($createTorg);
        $auth->add($viewTorg);
        $auth->add($updateTorg);
        $auth->add($deleteTorg);
        $auth->add($indexTorg);

        $viewOwnTorg = $auth->createPermission('viewOwnTorg');
        $viewOwnTorg->ruleName = $ownTorg->name;
        $auth->add($viewOwnTorg);

        $updateOwnTorg = $auth->createPermission('updateOwnTorg');
        $updateOwnTorg->ruleName = $ownTorg->name;
        $auth->add($updateOwnTorg);

        $deleteOwnTorg = $auth->createPermission('deleteOwnTorg');
        $deleteOwnTorg->ruleName = $ownTorg->name;
        $auth->add($deleteOwnTorg);

        $auth->addChild($viewOwnTorg, $viewTorg);
        $auth->addChild($updateOwnTorg, $updateTorg);
        $auth->addChild($deleteOwnTorg, $deleteTorg);

        $auth->addChild($createTorg, $create);
        $auth->addChild($viewTorg, $view);
        $auth->addChild($updateTorg, $update);
        $auth->addChild($deleteTorg, $delete);
        $auth->addChild($indexTorg, $index);

        // Lot

        $ownLot = new OwnLotRule();
        $auth->add($ownLot);

        $createLot = $auth->createPermission('createLot');
        $viewLot = $auth->createPermission('viewLot');
        $updateLot = $auth->createPermission('updateLot');
        $deleteLot = $auth->createPermission('deleteLot');
        $indexLot = $auth->createPermission('indexLot');

        $auth->add($createLot);
        $auth->add($viewLot);
        $auth->add($updateLot);
        $auth->add($deleteLot);
        $auth->add($indexLot);

        $viewOwnLot = $auth->createPermission('viewOwnLot');
        $viewOwnLot->ruleName = $ownLot->name;
        $auth->add($viewOwnLot);

        $updateOwnLot = $auth->createPermission('updateOwnLot');
        $updateOwnLot->ruleName = $ownLot->name;
        $auth->add($updateOwnLot);

        $deleteOwnLot = $auth->createPermission('deleteOwnLot');
        $deleteOwnLot->ruleName = $ownLot->name;
        $auth->add($deleteOwnLot);

        $auth->addChild($viewOwnLot, $viewLot);
        $auth->addChild($updateOwnLot, $updateLot);
        $auth->addChild($deleteOwnLot, $deleteLot);

        $auth->addChild($createLot, $create);
        $auth->addChild($viewLot, $view);
        $auth->addChild($updateLot, $update);
        $auth->addChild($deleteLot, $delete);
        $auth->addChild($indexLot, $index);

        // Order

        $ownOrder = new OwnOrderRule();
        $auth->add($ownOrder);

        $deleteOrder = $auth->createPermission('deleteOrder');
        $indexOrder = $auth->createPermission('indexOrder');

        $auth->add($deleteOrder);
        $auth->add($indexOrder);

        $deleteOwnOrder = $auth->createPermission('deleteOwnOrder');
        $deleteOwnOrder->ruleName = $ownOrder->name;
        $auth->add($deleteOwnOrder);

        $auth->addChild($deleteOwnOrder, $deleteOrder);

        $auth->addChild($deleteOrder, $delete);
        $auth->addChild($indexOrder, $index);

        // Owner

        $createOwner = $auth->createPermission('createOwner');
        $viewOwner = $auth->createPermission('viewOwner');
        $updateOwner = $auth->createPermission('updateOwner');
        $deleteOwner = $auth->createPermission('deleteOwner');
        $indexOwner = $auth->createPermission('indexOwner');

        $auth->add($createOwner);
        $auth->add($viewOwner);
        $auth->add($updateOwner);
        $auth->add($deleteOwner);
        $auth->add($indexOwner);

        $auth->addChild($createOwner, $create);
        $auth->addChild($viewOwner, $view);
        $auth->addChild($updateOwner, $update);
        $auth->addChild($deleteOwner, $delete);
        $auth->addChild($indexOwner, $index);

        // Roles
        
        $role = new UserRoleRule();
        $auth->add($role);

        $user = $auth->createRole('user');
        $user->ruleName  = $role->name;
        $auth->add($user);

        $arbitrator = $auth->createRole('arbitrator');
        $arbitrator->ruleName  = $role->name;
        $auth->add($arbitrator);

        $agent = $auth->createRole('agent');
        $agent->ruleName  = $role->name;
        $auth->add($agent);

        $manager = $auth->createRole('manager');
        $manager->ruleName  = $role->name;
        $auth->add($manager);

        $admin = $auth->createRole('admin');
        $admin->ruleName  = $role->name;
        $auth->add($admin);
 
        // User
        $auth->addChild($user, $viewOwnProfile);
        $auth->addChild($user, $updateOwnProfile);

        $auth->addChild($user, $createReport);
        $auth->addChild($user, $viewOwnReport);
        $auth->addChild($user, $updateOwnReport);
        $auth->addChild($user, $deleteOwnReport);
        $auth->addChild($user, $indexReport);
        $auth->addChild($user, $deleteOwnOrder);
        $auth->addChild($user, $indexOrder);

        // Arbitration manager
        $auth->addChild($arbitrator, $viewOwnTorg);
        $auth->addChild($arbitrator, $updateOwnTorg);
        $auth->addChild($arbitrator, $indexTorg);
        $auth->addChild($arbitrator, $viewOwnLot);
        $auth->addChild($arbitrator, $updateOwnLot);
        $auth->addChild($arbitrator, $indexLot);

        // Agent
        $auth->addChild($agent, $createTorg);
        $auth->addChild($agent, $viewOwnTorg);
        $auth->addChild($agent, $updateOwnTorg);
        $auth->addChild($agent, $deleteOwnTorg);
        $auth->addChild($agent, $indexTorg);
        $auth->addChild($agent, $createLot);
        $auth->addChild($agent, $viewOwnLot);
        $auth->addChild($agent, $updateOwnLot);
        $auth->addChild($agent, $deleteOwnLot);
        $auth->addChild($agent, $indexLot);

        // Manager
        $auth->addChild($manager, $viewReport);
        $auth->addChild($manager, $updateReport);
        $auth->addChild($manager, $deleteReport);
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
        $auth->addChild($admin, $viewUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $indexUser);

        $auth->addChild($admin, $createOwner);
        $auth->addChild($admin, $deleteOwner);

        $auth->addChild($admin, $deleteTorg);

        $auth->addChild($admin, $deleteLot);

        $auth->addChild($admin, $deleteOrder);

        $auth->addChild($admin, $create);
        $auth->addChild($admin, $view);
        $auth->addChild($admin, $update);
        $auth->addChild($admin, $delete);
        $auth->addChild($admin, $index);

        // Arbitrator can all that can User
        $auth->addChild($arbitrator, $user);
        // Agent can all that can User
        $auth->addChild($agent, $user);
        // Manager can all that can User
        $auth->addChild($manager, $user);
        
        // Admin can all that can Arbitrator
        $auth->addChild($admin, $arbitrator);
        // Admin can all that can Agent
        $auth->addChild($admin, $agent);
        // Admin can all that can Manager
        $auth->addChild($admin, $manager);
    }
}
