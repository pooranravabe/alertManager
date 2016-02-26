<?php

class Project extends Eloquent {

    protected $table = 'project';
    protected $fillable = array('id', 'user_id', 'title', 'updated_at', 'created_at');

    public static function getAllProjectById($userId) {
        $projectDetails = Project::where('user_id', $userId)->get();
        foreach ($projectDetails as $projectDetail) {
            if ($projectDetail->title == '') {
                continue;
            } else {
                $projectTitle[] = $projectDetail->title;
            }
        }
        return $projectTitle;
    }
    public static function getProjectOwnerIdByProjectId($project_id){
        $projectDetails = Project::where('id', $project_id)->first();
        if($projectDetails){
           return $projectDetails->user_id; 
        }
    }
	
	public static function getProjectNameByProjectId($project_id){
        $projectDetails = Project::where('id', $project_id)->first();
        if($projectDetails){
           return $projectDetails->title; 
        }
    }
//    public function project_channel() {
//        return $this->hasMany('ProjectChannels', 'project_id');
//    }
//    
    public function projectChannels() {
        return $this->hasMany('ProjectChannels', 'project_id');
    }
    
    public function content() {
        return $this->hasMany('Content', 'project_id');
    }
    
    public function event() {
        return $this->belongsTo('Events', 'project_id');
    }
    
    public function projectUsers(){
        return $this->hasMany('ProjectUsers', 'project_id');
    }

	public static function getAllProjects($userId, $orgId) {
        $project_ids = array();
		$projectDetails = Project::where('user_id', $userId)->where('org_id', $orgId)->get();
        if($projectDetails){
			foreach ($projectDetails as $projects) {
				$project_ids[] = $projects->id;
			}
			return $project_ids;
		}
		return 0;
    }
	public static function getAllProjects1($userId) {
		$project_ids = array();
        $projectDetails = Project::where('user_id', $userId)->get();
        if($projectDetails){
			foreach ($projectDetails as $projects) {
				$project_ids[] = $projects->id;
			}
			return $project_ids;
		}
		return 0;
    }
}
