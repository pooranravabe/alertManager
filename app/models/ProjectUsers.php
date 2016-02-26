<?php

class ProjectUsers extends Eloquent {

    protected $table = 'project_users';
    protected $fillable = array('owner_user_id', 'project_id','email','display_name','write_access');

    public function project() {
        return $this->belongsTo('Project', 'project_id');
    }
 
    public function user() {
        return $this->belongsTo('User', 'email');
    }

    public static function getUserRoleByUserId($user_id , $project_id){
    	$project_users = ProjectUsers::where('user_id', $user_id)->where('project_id', $project_id)->first();
        $current_user_role = '';
        if($project_users != ''){
            if($project_users->write_access == 1){
              $current_user_role = 'creator';
            }elseif ($project_users->analyst_access == 1){
              $current_user_role = 'analyst';
            }elseif ($project_users->editor_access == 1){
              $current_user_role = 'editor';
            }elseif ($project_users->special_access == 1){
              $current_user_role = 'chief';
            }  
        }
       return $current_user_role;  
    }

   public static function convertAnalystToWriter($user_id, $project_id){
      /* test done */
      $content_ids = Content::getPendingContentByUserId($project_id,$user_id);
      Approvals::whereIN('content_id', $content_ids)->delete();
      foreach ($content_ids as $content_id) {
        Alerts::deleteAlert($content_id);
      }
      Approvals::insertChiefDetails($project_id, $content_ids); 
   }
   public static function convertEditorToWriter($user_id, $project_id){
    /* test done */
      $content_ids = Content::getPendingContentByUserId($project_id,$user_id);
      Approvals::whereIN('content_id', $content_ids)->delete();
      Approvals::where('user_id', $user_id)->where('project_id', $project_id)->delete();  
      Approvals::insertChiefDetails($project_id, $content_ids);
      $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
                Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
                Approvals::resetAnalystApprovals($single_content['content_id'], $project_id); 
             break;
          }
        }
      } 
   }
   public static function convertChiefToWriter($user_id, $project_id){
       /* test done */
      Approvals::where('user_id', $user_id)->where('project_id', $project_id)->delete();   
   }

   public static function convertWriterToAnalyst($user_id, $project_id){
     /* test done */
      $content_ids = Content::getPendingContentByUserId($project_id,$user_id);
      Approvals::whereIN('content_id', $content_ids)->delete();
      foreach($content_ids as $content_id){
        Alerts::deleteAlertsUserId($content_id, $user_id);
        Approvals::resetAnalystApprovals($content_id, $project_id);
      }
   }

   public static function convertEditorToAnalyst($user_id, $project_id){
    /* test done */
      $content_ids = Content::getPendingContentByUserId($project_id, $user_id);
      Approvals::whereIN('content_id', $content_ids)->delete();
      Approvals::where('user_id', $user_id)->where('project_id', $project_id)->delete(); 
      foreach($content_ids as $content_id){
        Alerts::deleteAlertsUserId($content_id, $user_id);
        Approvals::resetAnalystApprovals($content_id, $project_id);
      }   
   }
   public static function convertChiefToAnalyst($user_id, $project_id){
      Approvals::where('user_id', $user_id)->where('project_id', $project_id)->delete();  
   }

   public static function convertAnalystToEditor($user_id, $project_id){
    /* test done */
      $content_ids = Content::getPendingContentByUserId($project_id,$user_id);
      Approvals::whereIN('content_id', $content_ids)->delete();
      Approvals::insertChiefDetails($project_id, $content_ids); 
      $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
               Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
               Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
              break;
          }
        }
      }  
   }
   public static function convertWriterToEditor($user_id, $project_id){
    /* test done */
    $content_ids = Content::getPendingContentByUserId($project_id,$user_id);
    Approvals::whereIN('content_id', $content_ids)->delete();
    Approvals::insertChiefDetails($project_id, $content_ids);
    $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
               Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
               Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
              break;
          }
        }
      }      
   }
   public static function convertChiefToEditor($user_id, $project_id, $approval_fullname){
    Approvals::where('user_id', $user_id)->where('project_id', $project_id)->delete();
    $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
              $approval_status = Approvals::where('content_id', $single_content['content_id'])->where('approvel_status', 0)->get();
               if($approval_status->count()){
                Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
                Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
               }else{
                $approve_status = Approvals::getContentApprovalStatus($single_content['content_id']);
                if($approve_status == 1){
                  Approvals::addApprovalAndNotify($user_id, $single_content['content_id'], $project_id, $approval_fullname);  
                }else{
                  Approvals::addRejectedAndNotify($user_id, $single_content['content_id'], $project_id);  
                }
               }
              break;
          }
        }
      }      
   }

   public static function convertAnalystToChief($user_id, $project_id, $current_user_id){
      $content_ids = Content::getPendingContentByUserId($project_id, $user_id);
      foreach ($content_ids as $content_id) {
        Approvals::where('content_id', $content_id)->delete(); 
        $content = Content::find($content_id);
        $content->author_id = $current_user_id;
        $content->save();
      }

     $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
               Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
               Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
              break;
            case 'creator':
            case 'editor':
            default:
              Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
              Approvals::addApprovalAndNotify($user_id, $single_content['content_id'], $project_id);  
            break;
          }
        }
      }      
   }

   public static function convertEditorToChief($user_id, $project_id, $current_user_id){
    Approvals::where('user_id', $user_id)->where('project_id', $project_id)->delete(); 
    $content_ids = Content::getPendingContentByUserId($project_id,$user_id);
      if($user_id == $current_user_id){
        $assigned_user_id = Project::getProjectOwnerIdByProjectId($project_id);
      }else{
        $assigned_user_id = $current_user_id;
      }
      foreach ($content_ids as $content_id) {
        Approvals::where('content_id', $content_id)->delete(); 
        $content = Content::find($content_id);
        $content->author_id = $assigned_user_id;
        $content->save();
      }
      
      $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
                 Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
                 Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
              break;
            case 'creator':
            case 'editor':
            default:
              Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
              Approvals::addApprovalAndNotify($user_id, $single_content['content_id'], $project_id);  
            break;
          }
        }
      }      
   }
   public static function convertWriterToChief($user_id, $project_id, $current_user_id){
     $content_ids = Content::getPendingContentByUserId($project_id,$user_id);
      foreach ($content_ids as $content_id) {
        Approvals::where('content_id', $content_id)->delete(); 
        $content = Content::find($content_id);
        $content->author_id = $current_user_id;
        $content->save();
      }

      $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
               Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
               Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
              break;
            case 'creator':
            case 'editor':
            default:
              Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
              Approvals::addApprovalAndNotify($user_id, $single_content['content_id'], $project_id);  
             break;
          }
        }
      }
   }

   public static function assignEditorToProject($user_id, $project_id){
    $pending_content_details = Content::getAllPendingContent($project_id);
    if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
                Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
                Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
            break;
          }
        }
      }
   }

   public static function assignChiefToProject($user_id, $project_id){
    $pending_content_details = Content::getAllPendingContent($project_id);
      if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$project_id);
          switch($role){
            case 'analyst':
               Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);
               Approvals::resetAnalystApprovals($single_content['content_id'], $project_id);;
              break;
            case 'creator':
            case 'editor':
            default:
            Alerts::deleteAlertsUserId($single_content['content_id'], $user_id);            
            Approvals::addApprovalAndNotify($user_id, $single_content['content_id'], $project_id);  
            break;
          }
        }
      }
   }
   public static function unsetUserRole($data){
    $role = ProjectUsers::getUserRoleByUserId($data['user_id'], $data['project_id']);
    switch ($data['role']) {
      case 'creator':
        $content_ids = Content::getPendingContentByUserId($data['project_id'], $data['user_id']);
        foreach ($content_ids as $content_id) {
          Approvals::where('content_id', $content_id)->delete(); 
          $content = Content::find($content_id);
          $content->author_id = $data['current_user_id'];
          $content->save();
        }
        Approvals::insertChiefDetails($data['project_id'], $content_ids);
        break;
      case 'analyst':
        $content_ids = Content::getPendingContentByUserId($data['project_id'], $data['user_id']);
        foreach ($content_ids as $content_id) {
          Alerts::deleteAlert($content_id);
          Approvals::where('content_id', $content_id)->delete(); 
          $content = Content::find($content_id);
          $content->author_id = $data['current_user_id'];
          $content->save();
        }
        Approvals::insertChiefDetails($data['project_id'], $content_ids);
        break;
      case 'editor':
        $current_user_id = $data['current_user_id'];
        if($current_user_id == $data['user_id']){
            $assigned_user_id = Project::getProjectOwnerIdByProjectId($data['project_id']);
        }else{
            $assigned_user_id = $data['current_user_id'];
        }
        Approvals::where('user_id', $data['user_id'])->where('project_id', $data['project_id'])->delete();
        $content_ids = Content::getPendingContentByUserId($data['project_id'], $data['user_id']);
        foreach ($content_ids as $content_id) {
          Alerts::deleteAlert($content_id);
          Approvals::where('content_id', $content_id)->delete(); 
          $content = Content::find($content_id);
          $content->author_id = $assigned_user_id;
          $content->save();
        }
        Approvals::insertChiefDetails($data['project_id'], $content_ids);
        
        $pending_content_details = Content::getAllPendingContent($data['project_id']);
        if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$data['project_id']);
          switch($role){
            case 'analyst':
               Alerts::deleteAlert($single_content['content_id']);
               Approvals::resetAnalystApprovals($single_content['content_id'], $data['project_id']);;
              break;
          }
        }
      }
        break;
      case 'chief':
        Approvals::where('user_id', $data['user_id'])->where('project_id',$data['project_id'])->delete();
        $pending_content_details = Content::getAllPendingContent($data['project_id']);
        if($pending_content_details){
        foreach ($pending_content_details as $single_content) {
          $role = ProjectUsers::getUserRoleByUserId($single_content['author_id'] ,$data['project_id']);
          switch($role){
            case 'analyst':
               Alerts::deleteAlert($single_content['content_id']);
               Approvals::resetAnalystApprovals($single_content['content_id'], $data['project_id']);;
              break;
          }
        }
      }
        break;
      default:
        # code...
        break;
    }
   }
  public static function getProjectChiefIds($project_id){
      $user_ids = array();
      $project_users = ProjectUsers::where('project_id',$project_id)->where('special_access', 1)->get();
      foreach ($project_users as $project_user) {
        $user_ids[] = $project_user->user_id;
      }
      return $user_ids;
  }

  public static function getProjectEditorIds($project_id){
      $user_ids = array();
      $project_users = ProjectUsers::where('project_id',$project_id)->where('editor_access', 1)->get();
      foreach ($project_users as $project_user) {
        $user_ids[] = $project_user->user_id;
      }
      return $user_ids;
  }

  public static function getAnalystFindByUserId($user_id, $project_id){
    $project_user_analyst = ProjectUsers::where('project_id', $project_id)->where('user_id',$user_id)->where('analyst_access', 1)->first(); 
    if($project_user_analyst){
      return true;
    }
    return false;
  }

   public static function getEditorFind($project_id){
    $project_user_analyst = ProjectUsers::where('project_id', $project_id)->where('user_id',$user_id)->where('analyst_access', 1)->first(); 
    if($project_user_analyst){
      return true;
    }
    return false;
  }
  public static function getmanageProject($user_id){
    $project_ids = array();
	$projects_manage = ProjectUsers::where('user_id',$user_id)->get(); 
    if($projects_manage){
		foreach ($projects_manage as $projects) {
			$project_ids[] = $projects->project_id;
		}
      return $project_ids;
    }
    return 0;
  }
}
