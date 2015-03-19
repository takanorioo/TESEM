<?php
/**
 * ProjectsController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
App::uses('AppController', 'Controller');
class ProjectsController extends AppController
{
    public $name = 'Project';
    public $uses = array('Project','UsersProject','User');
    public $helpers = array('Html', 'Form');
    public $layout = 'base';

    /**
     * beforeFilter
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->deny();
    }


     /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function index ()
    {
        $allow_non_project = true;
        $this->set('allow_non_project', $allow_non_project);

        $user = $this->getUser();

        //Public Project
        $projects = $this->Project->getPublicProjects();
        $this->set('public_projects', $public_projects);

        //Your Project
        $projects = $this->Project->getOwnProjects($user['id']);
        $this->set('projects', $projects);

        //Invited Project
        $invited_projects = $this->UsersProject->getInvitedProjects($user['id']);
        for($i = 0; $i < count($invited_projects); $i++){

            $project_id = $invited_projects[$i]['Project']['id'];
            $result = $this->Project->getRelatedProjects($project_id);
            $invited_projects[$i]['Project']['Member'] = $result['Member'];

        }
        $this->set('invited_projects', $invited_projects);
    
    }

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function set_project ($project_id = null)
    {
           //不正アクセス
        if (!isset($project_id)) {
            throw new BadRequestException();
        }

        $this->Session->write('Project.id', $project_id);

        $this->redirect(array('controller' => 'Top', 'action' => 'index'));
        return;
        
    }

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function add ()
    {
         if (!empty($this->request->data)) {

            $user_id= $this->me['User']['id'];

             // // トランザクション処理
            $this->Project->begin();

            $data['Project']['user_id'] = $user_id;
            $data['Project']['name'] = $this->request->data['Project']['name'];
            $data['Project']['type'] = $this->request->data['Project']['open_level'];

            if (!$this->Project->save($data['Project'],false,array('user_id','name','type'))) {
                $this->Project->rollback();
                throw new InternalErrorException();
            }

            $this->Project->commit();

            $this->redirect(array('controller' => 'Projects', 'action' => 'index'));
            return;
        }
    }



    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function delete ($id = null)
    {

        $user = $this->getUser();

        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }

        // authorization check
        if(!$this->Project->checkOwnProjects($user['id'], $id)){
            throw new BadRequestException();
        }

         // トランザクション処理始め
        $this->Project->begin();

        if (!$this->Project->delete($id)) {
            $this->Project->rollback();
            throw new BadRequestException();
        }

        $this->Project->commit();

        $this->Session->setFlash('You successfully delete.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
  
    
    }


    /**
     * invite
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function invite ()
    {
         if (!empty($this->request->data)) {


            $user_id = $this->User->getUserInfoByUserName($this->request->data['Project']['username']);

            if (empty($user_id)) {
                $this->Session->setFlash('This user name is not registerd..', 'default', array('class' => 'alert alert-danger'));
                $this->redirect($this->referer());
            }

            // トランザクション処理
            $this->UsersProject->begin();

            $data['UsersProject']['user_id'] = $user_id;
            $data['UsersProject']['project_id'] = $this->request->data['project_name'];

            if (!$this->UsersProject->save($data['UsersProject'],false,array('user_id','project_id'))) {
                $this->UsersProject->rollback();
                throw new InternalErrorException();
            }

            $this->UsersProject->commit();

            $this->Session->setFlash('You successfully invite member.', 'default', array('class' => 'alert alert-success'));
            $this->redirect($this->referer());
        }
    }
}
