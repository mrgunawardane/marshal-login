<?php

namespace App\Controllers;


use \Core\View;
use Core;
use App\Models\Project;

class Projects extends \Core\Controller{

    public function dashboardAction(){

        $projects = Project::getAllProjects($_COOKIE['myCookie']);

        // foreach($projects as $project){
        //     echo $project['projectName']."<br />\n";
        // }

        View::render('Project/dashboard.html', $projects);
    }

    public function createAction(){
        // $userId = -1;

        // if(isset($_COOKIE['myCookie'])){
        //     $userId = $_COOKIE['myCookie'];
        // }

        View::render('Project/create.html');
    }

    public function registerAction(){
        $successed = Project::createProject(array(
            'projectName' => $_POST['projectName'],
            'description' => $_POST['description'],
            'field' => $_POST['field'],
            'leaderId' => $_COOKIE['myCookie']
        ));

        if($successed){

            $updated = Project::insertProjectMember(
                array(
                    'projectId' => $successed,
                    'userId' => $_COOKIE['myCookie']
                )
            );

            if($updated){
                header('Location: http://localhost/mvcFramework3/public/projects/dashboard');
                exit;
            }

            
        }else{
            echo 'unsuccess';
        }
    }
}