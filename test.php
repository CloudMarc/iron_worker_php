<?php
include("SimpleWorker.class.php");


function tolog($name, $variable, $display = false){
    file_put_contents("log/$name.log", print_r($variable, true));
    if ($display){echo "{$name}: ".var_export($variable,true)."\n";}
}

$name = "helloPHP-".date('Y-m-d');

#$sw = new SimpleWorker('config_sw.ini');
$sw = new SimpleWorker('config_production.ini');
$sw->debug_enabled = true;


# ========================== Projects ===========================

echo "\n--Get Project List------------------------------------\n";
$projects = $sw->getProjects();
tolog('projects', $projects);

echo "\n--Get Project Details---------------------------------\n";
$project_details = $sw->getProjectDetails($projects[0]->id);
tolog('project_details', $project_details);

echo "\n--Posting Project-------------------------------------\n";
$project_id = $sw->postProject('TestNewProject');
tolog('post_project', $project_id, true);

/*
echo "\n--Deleting Project-------------------------------------\n";
# TODO: {"msg":"Method DELETE not allowed","status_code":405}
$res = $sw->deleteProject($project_id);
tolog('delete_project', $res);
*/

# =========================== Codes =============================

echo "\n--Get Codes-------------------------------------------\n";
$codes = $sw->getCodes($projects[0]->id);
tolog('codes', $codes);

echo "\n--Posting Code----------------------------------------\n";
$zipName = $name.'.zip';
$files_to_zip = array('testTask.php');
# if true, good; if false, zip creation failed
$zipFile = SimpleWorker::createZip($files_to_zip, $zipName, true);
$res = $sw->postCode($projects[0]->id, 'testTask.php', $zipName, $name);
tolog('post_code', $res);

echo "\n-Get Code Details--------------------------------------\n";
$code_details = $sw->getCodeDetails($codes[0]->id, $projects[0]->id);
tolog('get_code_details', $code_details);

# =========================== Tasks =============================

echo "\n--Get Tasks-------------------------------------------\n";
$tasks = $sw->getTasks($projects[0]->id);
tolog('tasks', $tasks);

echo "\n--Get Task Log----------------------------------------\n";
$log = $sw->getLog($projects[0]->id, $tasks[0]->id);
tolog('task_log', $log);

echo "\n--Posting Task----------------------------------------\n";
$task_id = $sw->postTask($projects[0]->id, $name);
tolog('post_task', $task_id, true);

/*
echo "\n--Deleting Task---------------------------------------\n";
# TODO: <empty responce>
$res = $sw->deleteTask($projects[0]->id, $task_id);
tolog('delete_task', $res);
*/

echo "\n--Set Task Progress-----------------------------------\n";
$res = $sw->setTaskProgress($projects[0]->id, $task_id, 50, 'Job half-done');
tolog('set_task_progress', $res, true);

/*
echo "\n--Cancel Task-----------------------------------\n";
# TODO: returns {"goto":"http://www.iron.io","version":"2.0.12"}
# or {"msg":"Method POST not allowed","status_code":405}
$res = $sw->cancelTask($projects[0]->id, $task_id);
tolog('cancel_task', $res, true);
*/


# ========================== Schedules ==========================

echo "\n--Get Schedules----------------------------------------\n";
$schedules = $sw->getSchedules($projects[0]->id);
tolog('schedules', $schedules);


echo "\n--Posting Simple Shedule--------------------------------------\n";
$schedule_id = $sw->postScheduleSimple($projects[0]->id, $name, 10);
tolog('post_schedule_simple', $schedule_id, true);

echo "\n--Posting Advanced Shedule--------------------------------------\n";
$start_at = SimpleWorker::dateRfc3339(time());
echo ">>>$start_at<<<\n";
$schedule_id = $sw->postScheduleAdvanced($projects[0]->id, $name, $start_at, 50, null, 4, 0);
tolog('post_schedule_advanced', $schedule_id, true);

/*
echo "\n--Deleting Shedule-------------------------------------\n";
# TODO: <empty responce>
$res = $sw->deleteSchedule($projects[0]->id, $schedule_id);
tolog('delete_schedule', $res, true);
*/









echo "\ndone\n";