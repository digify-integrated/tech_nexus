<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/menu-item-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$menuItemModel = new MenuItemModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = array();
    
    switch ($type) {
        # Menu group table
        case 'generate navbar menu':
            $menu = '';
            $sql = $databaseModel->getConnection()->prepare('CALL buildMenuGroup(:userID)');
            $sql->bindValue(':userID', $user_id);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $menuGroupID = $row['menu_group_id'];
                $menuGroupName = $row['menu_group_name'];

                $menu .= '<li class="pc-item pc-caption">
                            <label>'. $menu_group_name .'</label>
                        </li>';

                $menu .= $menuItemModel->buildMenuItem($user_id, $menuGroupID);
            }

            echo json_encode($response);
        break;
    }
}

?>