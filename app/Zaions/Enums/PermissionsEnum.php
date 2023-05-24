<?php

namespace App\Zaions\Enums;


enum PermissionsEnum: string
{
    // Role
  case viewAny_role = 'viewAny_role';
  case view_role = 'view_role';
  case create_role = 'create_role';
  case update_role = 'update_role';
  case delete_role = 'delete_role';
  case replicate_role = 'replicate_role';
  case restore_role = 'restore_role';
  case forceDelete_role = 'forceDelete_role';

    // Permission
  case viewAny_permission = 'viewAny_permission';
  case view_permission = 'view_permission';
  case create_permission = 'create_permission';
  case update_permission = 'update_permission';
  case delete_permission = 'delete_permission';
  case replicate_permission = 'replicate_permission';
  case restore_permission = 'restore_permission';
  case forceDelete_permission = 'forceDelete_permission';

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

    // Task
  case viewAny_task = 'viewAny_task';
  case view_task = 'view_task';
  case create_task = 'create_task';
  case update_task = 'update_task';
  case delete_task = 'delete_task';
  case replicate_task = 'replicate_task';
  case restore_task = 'restore_task';
  case forceDelete_task = 'forceDelete_task';

    // History
  case viewAny_history = 'viewAny_history';
  case view_history = 'view_history';
  case create_history = 'create_history';
  case update_history = 'update_history';
  case delete_history = 'delete_history';
  case replicate_history = 'replicate_history';
  case restore_history = 'restore_history';
  case forceDelete_history = 'forceDelete_history';

    // Attachment
  case viewAny_attachment = 'viewAny_attachment';
  case view_attachment = 'view_attachment';
  case create_attachment = 'create_attachment';
  case update_attachment = 'update_attachment';
  case delete_attachment = 'delete_attachment';
  case replicate_attachment = 'replicate_attachment';
  case restore_attachment = 'restore_attachment';
  case forceDelete_attachment = 'forceDelete_attachment';

    // Comment
  case viewAny_comment = 'viewAny_comment';
  case view_comment = 'view_comment';
  case create_comment = 'create_comment';
  case update_comment = 'update_comment';
  case delete_comment = 'delete_comment';
  case replicate_comment = 'replicate_comment';
  case restore_comment = 'restore_comment';
  case forceDelete_comment = 'forceDelete_comment';

    // Reply
  case viewAny_reply = 'viewAny_reply';
  case view_reply = 'view_reply';
  case create_reply = 'create_reply';
  case update_reply = 'update_reply';
  case delete_reply = 'delete_reply';
  case replicate_reply = 'replicate_reply';
  case restore_reply = 'restore_reply';
  case forceDelete_reply = 'forceDelete_reply';

    // Impersonation
  case can_impersonate = 'can_impersonate';
  case canBe_impersonate = 'canBe_impersonate';

  // case viewAny_ = 'viewAny_';
  // case view_ = 'view_';
  // case create_ = 'create_';
  // case update_ = 'update_';
  // case delete_ = 'delete_';
  // case replicate_ = 'replicate_';
  // case restore_ = 'restore_';
  // case forceDelete_ = 'forceDelete_';
}
