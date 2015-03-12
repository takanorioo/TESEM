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
    public $uses = array('Project');
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

            $this->redirect(array('controller' => 'Top', 'action' => 'index'));
            return;
        }
    }
}
