<?php

namespace App\Zaions\Enums;


enum PermissionsEnum: string
{
    // viewAny means user should be able to see nova resource (table list) page of that Model/Resource

    // Role
  case viewAny_role = 'viewAny_role';
  case view_role = 'view_role';
  case create_role = 'create_role';
  case update_role = 'update_role';
  case delete_role = 'delete_role';
  case replicate_role = 'replicate_role';
  case restore_role = 'restore_role';
  case forceDelete_role = 'forceDelete_role';
  case viewResource_role = 'viewResource_role';

    // Permission
  case viewAny_permission = 'viewAny_permission';
  case view_permission = 'view_permission';
  case create_permission = 'create_permission';
  case update_permission = 'update_permission';
  case delete_permission = 'delete_permission';
  case replicate_permission = 'replicate_permission';
  case restore_permission = 'restore_permission';
  case forceDelete_permission = 'forceDelete_permission';
  case viewResource_permission = 'viewResource_permission';

    // Dashboard
  case view_dashboard = 'view_dashboard';

    // User
  case viewAny_user = 'viewAny_user';
  case view_user = 'view_user';
  case create_user = 'create_user';
  case update_user = 'update_user';
  case delete_user = 'delete_user';
  case replicate_user = 'replicate_user';
  case restore_user = 'restore_user';
  case forceDelete_user = 'forceDelete_user';
  case viewResource_user = 'viewResource_user';

    // Attachment
  case viewAny_attachment = 'viewAny_attachment';
  case view_attachment = 'view_attachment';
  case create_attachment = 'create_attachment';
  case update_attachment = 'update_attachment';
  case delete_attachment = 'delete_attachment';
  case replicate_attachment = 'replicate_attachment';
  case restore_attachment = 'restore_attachment';
  case forceDelete_attachment = 'forceDelete_attachment';
  case viewResource_attachment = 'viewResource_attachment';

    // Comment
  case viewAny_comment = 'viewAny_comment';
  case view_comment = 'view_comment';
  case create_comment = 'create_comment';
  case update_comment = 'update_comment';
  case delete_comment = 'delete_comment';
  case replicate_comment = 'replicate_comment';
  case restore_comment = 'restore_comment';
  case forceDelete_comment = 'forceDelete_comment';
  case viewResource_comment = 'viewResource_comment';

    // Reply
  case viewAny_reply = 'viewAny_reply';
  case view_reply = 'view_reply';
  case create_reply = 'create_reply';
  case update_reply = 'update_reply';
  case delete_reply = 'delete_reply';
  case replicate_reply = 'replicate_reply';
  case restore_reply = 'restore_reply';
  case forceDelete_reply = 'forceDelete_reply';
  case viewResource_reply = 'viewResource_reply';

    // Impersonation
  case can_impersonate = 'can_impersonate';
  case canBe_impersonate = 'canBe_impersonate';

    // Project
  case viewAny_project = 'viewAny_project';
  case view_project = 'view_project';
  case create_project = 'create_project';
  case update_project = 'update_project';
  case delete_project = 'delete_project';
  case replicate_project = 'replicate_project';
  case restore_project = 'restore_project';
  case forceDelete_project = 'forceDelete_project';
  case viewResource_project = 'viewResource_project';
  case viewLens_projectRebateListPage = 'viewLens_projectRebateListPage';

    // Profile
  case view_profile = 'view_profile';
  case update_profile = 'update_profile';
  case delete_profile = 'delete_profile';

    // 2FA
  case view_2fa = 'view_2fa';
  case create_2fa = 'create_2fa'; // setup
  case update_2fa = 'update_2fa'; // update
  case delete_2fa = 'delete_2fa'; // remove


    // Transactions Permissions
  case viewAny_projectTransaction = 'viewAny_projectTransaction';
  case view_projectTransaction = 'view_projectTransaction';
  case create_projectTransaction = 'create_projectTransaction';
  case update_projectTransaction = 'update_projectTransaction';
  case delete_projectTransaction = 'delete_projectTransaction';
  case replicate_projectTransaction = 'replicate_projectTransaction';
  case restore_projectTransaction = 'restore_projectTransaction';
  case forceDelete_projectTransaction = 'forceDelete_projectTransaction';
  case viewResource_projectTransactionLens = 'viewResource_projectTransactionLens';
  case viewLens_personalProjectTransactionLens = 'viewLens_personalProjectTransactionLens'; // transactions this user made

  // case viewAny_ = 'viewAny_';
  // case view_ = 'view_';
  // case create_ = 'create_';
  // case update_ = 'update_';
  // case delete_ = 'delete_';
  // case replicate_ = 'replicate_';
  // case restore_ = 'restore_';
  // case forceDelete_ = 'forceDelete_';
}
