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
class BehaviorController extends AppController
{
    public $name = 'Behavior';
    public $uses = array('Behavior','BehaviorRelations');
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
    public function index ($method_id = null)
    {
         //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        //要素の取得
        $behaviors = $this->Behavior->getBehaviorElement($method_id);
        $this->set('behaviors', $behaviors);

        if(!empty($behaviors)) {

            for($i = 0; $i < count($behaviors); $i++) {
                $behabior_relation[$behaviors[$i]['Behavior']['id']] = $behaviors[$i]['Label']['name'];
            }
            $this->set('behabior_relation', $behabior_relation);

            $relation_key = array_keys($behabior_relation);

            for($i = 0; $i < count($behabior_relation); $i++) {
                $option_behabior_relation[$i]['id'] = $relation_key[$i];
                $option_behabior_relation[$i]['name'] = $behabior_relation[$relation_key[$i]];
            }
            $this->set('option_behabior_relation', $option_behabior_relation);



            //データ構造の加工
            $behavior_count = count($behaviors);
            $this->set('behavior_count', $behavior_count);


            $behaviors_count = count($behaviors);
            for($i = 0; $i < $behaviors_count; $i++) {
                $behaviors_data['Behavior']['id'][] = $behaviors[$i]['Behavior']['id'];
                $behaviors_data['Behavior']['type'][] = $behaviors[$i]['Behavior']['type'];
                $behaviors_data['Behavior']['label_id'][] = $behaviors[$i]['Behavior']['label_id'];
                $behaviors_data['Behavior']['order'][] = $behaviors[$i]['Behavior']['order'];
                $behaviors_data['Behavior']['name'][] = $behaviors[$i]['Label']['name'];
            }
            $this->set('behaviors_data', $behaviors_data);


            $behavior_action_count = 0;


            for($i = 0; $i < count($behaviors); $i++) {
                for($j = 0; $j < count($behaviors[$i]['BehaviorRelations']); $j++) {  
                    $behaviors_data['BehaviorRelations']['id'][] = $behaviors[$i]['BehaviorRelations'][$j]['id'];
                    $behaviors_data['BehaviorRelations']['behavior_id'][] = $behaviors[$i]['BehaviorRelations'][$j]['behavior_id'];
                    $behaviors_data['BehaviorRelations']['action'][] = $behaviors[$i]['BehaviorRelations'][$j]['action'];
                    $behaviors_data['BehaviorRelations']['guard'][] = $behaviors[$i]['BehaviorRelations'][$j]['guard'];
                    $behaviors_data['BehaviorRelations']['behavior_relation_id'][] = $behaviors[$i]['BehaviorRelations'][$j]['behavior_relation_id'];
                    $behaviors_data['BehaviorRelations']['order'][] = $behaviors[$i]['BehaviorRelations'][$j]['order'];
                    $behavior_action_count ++;
                }
            }

            $this->set('behavior_action_count', $behavior_action_count);
            $this->set('behaviors_data', $behaviors_data);
        }

        $this->set('method_id', $method_id);

        if (!empty($this->request->data['Behavior'])) {

            $request_data = $this->request->data;

            for($i = 0; $i < count($request_data['Behavior']['type']); $i++) {

                //初期化
                $data = array();

                // トランザクション処理
                $this->Behavior->create();
                $this->Behavior->begin();

                if(!empty($request_data['Behavior']['id'][$i])) {
                    $data['Behavior']['id'] = $request_data['Behavior']['id'][$i];
                }

                $data['Behavior']['type'] = $request_data['Behavior']['type'][$i];
                $data['Behavior']['label_id'] = $request_data['Behavior']['label_id'][$i];
                $data['Behavior']['method_id'] = $method_id;

                if (!$this->Behavior->save($data['Behavior'],false,array('id','type','label_id','method_id'))) {
                    $this->Behavior->rollback();
                    throw new InternalErrorException();
                }

                $this->Behavior->commit();
            }
            $this->Session->setFlash('You successfully Set Target Function.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Behavior', 'action' => 'index',$method_id));
        }


        if (!empty($this->request->data['editAction'])) {

            $request_data = $this->request->data;

            for($i = 0; $i < count($request_data['BehaviorRelations']['behavior_id']); $i++) {

                //初期化
                $data = array();

                // トランザクション処理
                $this->BehaviorRelations->create();
                $this->BehaviorRelations->begin();

                if(!empty($request_data['BehaviorRelations']['id'][$i])) {
                    $data['BehaviorRelations']['id'] = $request_data['BehaviorRelations']['id'][$i];
                }

                $data['BehaviorRelations']['behavior_id'] = $request_data['BehaviorRelations']['behavior_id'][$i];
                $data['BehaviorRelations']['behavior_relation_id'] = $request_data['BehaviorRelations']['behavior_relation_id'][$i];
                $data['BehaviorRelations']['action'] = $request_data['BehaviorRelations']['action'][$i];
                $data['BehaviorRelations']['guard'] = $request_data['BehaviorRelations']['guard'][$i];
                $data['BehaviorRelations']['order'] = $request_data['BehaviorRelations']['order'][$i];

                if (!$this->BehaviorRelations->save($data['BehaviorRelations'],false,array('id','behavior_id','behavior_relation_id','action','guard','order'))) {
                    $this->BehaviorRelations->rollback();
                    throw new InternalErrorException();
                }

                $this->BehaviorRelations->commit();
            }
            $this->Session->setFlash('You successfully Set Target Function.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Behavior', 'action' => 'index',$method_id));
        }

        if(!empty($behaviors)) {
            $this->request->data = $behaviors_data;
        }
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function element_delete($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->Behavior->begin();

        if (!$this->Behavior->delete($id)) {
            $this->Behavior->rollback();
            throw new BadRequestException();
        }

        $this->Behavior->commit();

        $this->Session->setFlash('You successfully delete element.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function action_delete($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->BehaviorRelations->begin();

        if (!$this->BehaviorRelations->delete($id)) {
            $this->BehaviorRelations->rollback();
            throw new BadRequestException();
        }

        $this->BehaviorRelations->commit();

        $this->Session->setFlash('You successfully delete element.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
    }

}
