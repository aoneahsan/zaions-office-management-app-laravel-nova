<?php

namespace App\Zaions\Enums;


enum PermissionsEnum: string
{
    // Role
  case viewAll_role = 'viewAll_role';
  case view_role = 'view_role';
  case add_role = 'add_role';
  case edit_role = 'edit_role';
  case delete_role = 'delete_role';

    // Permission
  case viewAll_permission = 'viewAll_permission';
  case view_permission = 'view_permission';
  case add_permission = 'add_permission';
  case edit_permission = 'edit_permission';
  case delete_permission = 'delete_permission';

    // User
  case viewAll_user = 'viewAll_user';
  case view_user = 'view_user';
  case add_user = 'add_user';
  case edit_user = 'edit_user';
  case delete_user = 'delete_user';

    // Task
  case viewAll_task = 'viewAll_task';
  case view_task = 'view_task';
  case add_task = 'add_task';
  case edit_task = 'edit_task';
  case delete_task = 'delete_task';

    // History
  case viewAll_history = 'viewAll_history';
  case view_history = 'view_history';
  case add_history = 'add_history';
  case edit_history = 'edit_history';
  case delete_history = 'delete_history';

    // Attachment
  case viewAll_attachment = 'viewAll_attachment';
  case view_attachment = 'view_attachment';
  case add_attachment = 'add_attachment';
  case edit_attachment = 'edit_attachment';
  case delete_attachment = 'delete_attachment';

    // Comment
  case viewAll_comment = 'viewAll_comment';
  case view_comment = 'view_comment';
  case add_comment = 'add_comment';
  case edit_comment = 'edit_comment';
  case delete_comment = 'delete_comment';

  // case viewAll_ = 'viewAll_';
  // case view_ = 'view_';
  // case add_ = 'add_';
  // case edit_ = 'edit_';
  // case delete_ = 'delete_';
}
