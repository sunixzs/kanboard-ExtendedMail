<?php


namespace Kanboard\Plugin\ExtendedMail\Helper;

use Kanboard\Core\Base;

/**
 * Class helper for mail temlates 
 *
 * @package Kanboard\Plugin\ExtendedMail\Helper
 * @author  Rens Sikma <rens@atcomputing.nl>
 */
class MailTemplate extends Base
{

    # if you update you should also update variableExpansion
    public static $pattern = array ('%task_id' , '%task_title',  '%task_discriptio',    '%project_id', '%project_email',   '%user_name'     , '%user_email');

     /**
     * @access public
     * @param  array $project
     * @param  array $task
     * @param  string $msg
     * @return string
     */
    public function variableExpansion($project,$task,$msg)
    {
        $user = $this->userSession->getAll();
       // TODO remove repetion ?/use transposer array
        $pattern   = array ('%task_id' , '%task_title',  '%task_discriptio',    '%project_id',
                            '%project_email',   '%user_name'     , '%user_email');
        $variables = array ($task['id'], $task['title'] , $task['description'], $project['id'],
                            $project['email'], $user['username'], $user['email'] );

        return str_replace($pattern ,$variables ,$msg);
    }

     /**
     * @param  integer $project_id
     * @return string 
     */
    public function Treply_to($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_reply_to', '%user_email');
    }

     /**
     * @param  integer $project_id
     * @return string 
     */
    public function Tsubject($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_subject', '');
    }

     /**
     * @param  integer $project_id
     * @return string 
     */
    public function Tto($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_to', '');
    }

     /**
     * @param  integer $project_id
     * @return string 
     */
    public function Tbody($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_body', '');
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function subject($project,$task)
    {
        $subject_template = self::Tsubject ($project['id']);
        return self::variableExpansion($project, $task, $subject_template);
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function reply_to($project,$task)
    {
        $reply_to_template = self::Treply_to($project['id']);
        return self::variableExpansion($project, $task, $reply_to_template);
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function to($project,$task)
    {
        $to_template= self::Tto($project['id']);
        return self::variableExpansion($project, $task, $to_template);
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function body($project,$task)
    {
        $body_template = self::Tbody($project['id']);
        return self::variableExpansion($project, $task, $body_template);
    }
}