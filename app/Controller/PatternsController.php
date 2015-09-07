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
class PatternsController extends AppController
{
    public $name = 'Patterns';
    public $uses = array(
	'Project',
	'UsersProject',
	'User',
        'Pattern',
        'PatternOcl',
        'PatternElement',
        'PatternAttribute',
        'PatternMethod',
        'PatternRelation',
//        'PatternRequirement',
        'PatternCondition',
//        'PatternRequirementAction',
        'PatternAction',
        'PatternBehavior',
        'PatternBehaviorRelations'
        );

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
	$this->set("title_for_layout","TESEM");
    }


    function beforeRender()
    {
        $pattern_id = $this->Session->read('Pattern.id');
        if(!empty($pattern_id)) {
            //要素の読み込み始まり
            $pattern_elements = $this->PatternElement->getPatternElements($pattern_id); 
            
            for($i = 0; $i < count($pattern_elements); $i++) 
            {
                if(count($pattern_elements[$i]['PatternAttribute']) + count($pattern_elements[$i]['PatternMethod']) > 3) {
                    $num = count($pattern_elements[$i]['PatternAttribute']) + count($pattern_elements[$i]['PatternMethod']);
                    $pattern_elements[$i]['height'] = 100 + ($num - 3) * 20 ;
                } else {
                    $pattern_elements[$i]['height'] = 100;
                }
            }
            $methods = array();
            if(!empty($pattern_elements))
            {
                for($i = 0; $i < count($pattern_elements); $i++) {
                    $relation[$pattern_elements[$i]['PatternElement']['id']] = $pattern_elements[$i]['PatternElement']['element'];
                }
                $this->set('relation', $relation);
                $relation_key = array_keys($relation);
                for($i = 0; $i < count($relation); $i++) {
                    $option_relation[$i]['id'] = $relation_key[$i];
                    $option_relation[$i]['name'] = $relation[$relation_key[$i]];
                }
                $this->set('option_relation', $option_relation);
                for($i = 0; $i < count($pattern_elements); $i++) {
                    $width = 110;
                    $tmp_width = 0;
                    //メソッドの長さを計算
                    for($j = 0; $j < count($pattern_elements[$i]['PatternMethod']); $j++) {
                        if(!empty($pattern_elements[$i]['PatternMethod'])) {
                            $methods[$pattern_elements[$i]['PatternMethod'][$j]['id']] = $pattern_elements[$i]['PatternElement']['element']." : ".$pattern_elements[$i]['PatternMethod'][$j]['name'];
                            if(strlen($pattern_elements[$i]['PatternMethod'][$j]['name']) > 12) {
                                $num = strlen($pattern_elements[$i]['PatternMethod'][$j]['name']);
                                $tmp_width = 110 + ($num - 12) * 8 ;
                            }
                        }
                        if($tmp_width > $width) {
                            $width = $tmp_width;
                        }
                    }
                    //ラベル名の長さを計算
                    if(!empty($pattern_elements[$i]['PatternElement'])) {
                        if(strlen($pattern_elements[$i]['PatternElement']['element']) > 12) {
                            $num = strlen($pattern_elements[$i]['PatternElement']['element']);
                            $tmp_width = 110 + ($num - 12) * 8 ;
                        }
                    }
                    if($tmp_width > $width){
                        $width = $tmp_width;
                    }
                    //インターフェースの長さを計算
                    if(!empty($pattern_elements[$i]['PatternElement'])) {
                        if(strlen($pattern_elements[$i]['PatternElement']['interface']) > 12) {
                            $num = strlen($pattern_elements[$i]['PatternElement']['interface']);
                            $tmp_width = 110 + ($num - 12) * 8 ;
                        }
                    }
                    if($tmp_width > $width) {
                        $width = $tmp_width;
                    }
                    $pattern_elements[$i]['width'] = $width;
                }
                $this->set('methods', $methods);
                $this->set('pattern_elements', $pattern_elements);
            }
        }
    }

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function index ()
    {
        $patterns = $this->Pattern->getPatterns();
        $this->set('patterns', $patterns);

    }

    /**
     * structure
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function structure ($pattern_id = null)
    {
        $this->Session->write('Pattern.id', $pattern_id);
        $this->set('pattern_id', $pattern_id);

        $pattern = $this->Pattern->getPattern($pattern_id);
        $this->set('pattern', $pattern);
    }

    /**
     * behavior
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function behavior ($pattern_id = null)
    {
        $this->Session->write('Pattern.id', $pattern_id);
        $this->set('pattern_id', $pattern_id);

        //Behavior
	if(!empty($this->PatternBehavior)){
	        $behaviors = $this->PatternBehavior->getBehaviorElement($pattern_id);
	        $this->set('behaviors', $behaviors);
	}
        if(!empty($behaviors)) {
            for($i = 0; $i < count($behaviors); $i++) {
                $behabior_relation[$behaviors[$i]['PatternBehavior']['id']] = $behaviors[$i]['PatternElement']['element'];
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
                $behaviors_data['PatternBehavior']['id'][] = $behaviors[$i]['PatternBehavior']['id'];
                $behaviors_data['PatternBehavior']['type'][] = $behaviors[$i]['PatternBehavior']['type'];
                $behaviors_data['PatternBehavior']['pattern_element_id'][] = $behaviors[$i]['PatternBehavior']['pattern_element_id'];
                $behaviors_data['PatternBehavior']['order'][] = $behaviors[$i]['PatternBehavior']['order'];
                $behaviors_data['PatternBehavior']['name'][] = $behaviors[$i]['PatternElement']['element'];
            }
            $this->set('behaviors_data', $behaviors_data);

            $behavior_action_count = 0;


            for($i = 0; $i < count($behaviors); $i++) {
                for($j = 0; $j < count($behaviors[$i]['PatternBehaviorRelations']); $j++) {  
                    $behaviors_data['PatternBehaviorRelations']['id'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['id'];
                    $behaviors_data['PatternBehaviorRelations']['pattern_behavior_id'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['pattern_behavior_id'];
                    $behaviors_data['PatternBehaviorRelations']['action'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['action'];
                    $behaviors_data['PatternBehaviorRelations']['guard'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['guard'];
                    $behaviors_data['PatternBehaviorRelations']['behavior_relation_id'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['behavior_relation_id'];
                    $behaviors_data['PatternBehaviorRelations']['order'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['order'];
                    $behavior_action_count ++;
                }
            }

            $this->set('behavior_action_count', $behavior_action_count);
            $this->set('behaviors_data', $behaviors_data);
        }

        $pattern = $this->Pattern->getPattern($pattern_id);
        $this->set('pattern', $pattern);

        if (!empty($this->request->data['PatternBehavior'])) {

            $request_data = $this->request->data;

            for($i = 0; $i < count($request_data['PatternBehavior']['type']); $i++) {

                //初期化
                $data = array();

                // トランザクション処理
                $this->PatternBehavior->create();
                $this->PatternBehavior->begin();

                if(!empty($request_data['PatternBehavior']['id'][$i])) {
                    $data['PatternBehavior']['id'] = $request_data['PatternBehavior']['id'][$i];
                }

                $data['PatternBehavior']['type'] = $request_data['PatternBehavior']['type'][$i];
                $data['PatternBehavior']['pattern_element_id'] = $request_data['PatternBehavior']['pattern_element_id'][$i];
                $data['PatternBehavior']['pattern_id'] = $pattern_id;

                if (!$this->PatternBehavior->save($data['PatternBehavior'],false,array('id','type','pattern_element_id','pattern_id'))) {
                    $this->PatternBehavior->rollback();
                    throw new InternalErrorException();
                }

                $this->PatternBehavior->commit();
            }
            $this->Session->setFlash('You successfully Edit PatternBehavior.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Patterns', 'action' => 'behavior',$pattern_id));
        }


        if (!empty($this->request->data['editAction'])) {

            $request_data = $this->request->data;

            for($i = 0; $i < count($request_data['PatternBehaviorRelations']['pattern_behavior_id']); $i++) {

                //初期化
                $data = array();

                // トランザクション処理
                $this->PatternBehaviorRelations->create();
                $this->PatternBehaviorRelations->begin();

                if(!empty($request_data['PatternBehaviorRelations']['id'][$i])) {
                    $data['PatternBehaviorRelations']['id'] = $request_data['PatternBehaviorRelations']['id'][$i];
                }

                $data['PatternBehaviorRelations']['pattern_behavior_id'] = $request_data['PatternBehaviorRelations']['pattern_behavior_id'][$i];
                $data['PatternBehaviorRelations']['behavior_relation_id'] = $request_data['PatternBehaviorRelations']['behavior_relation_id'][$i];
                $data['PatternBehaviorRelations']['action'] = $request_data['PatternBehaviorRelations']['action'][$i];
                $data['PatternBehaviorRelations']['guard'] = $request_data['PatternBehaviorRelations']['guard'][$i];
                $data['PatternBehaviorRelations']['order'] = $request_data['PatternBehaviorRelations']['order'][$i];

                if (!$this->PatternBehaviorRelations->save($data['PatternBehaviorRelations'],false,array('id','pattern_behavior_id','behavior_relation_id','action','guard','order'))) {
                    $this->PatternBehaviorRelations->rollback();
                    throw new InternalErrorException();
                }

                $this->PatternBehaviorRelations->commit();
            }
            $this->Session->setFlash('You successfully Set Edit PatternBehavior..', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Patterns', 'action' => 'behavior',$pattern_id));
        }

        if(!empty($behaviors)) {
            $this->request->data = $behaviors_data;
        }

    }

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function detail ($pattern_id = null)
    {
        $this->Session->write('Pattern.id', $pattern_id);

        $pattern = $this->Pattern->getPattern($pattern_id);
        $this->set('pattern', $pattern);

    }

    /**
     * elements
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function elements_list ($pattern_id = null)
    {
        $this->Session->write('Pattern.id', $pattern_id);
        $this->set('pattern_id', $pattern_id);

        $pattern = $this->Pattern->getPattern($pattern_id);
        $this->set('pattern', $pattern);

        $elements_list = $this->PatternElement->getPatternElements($pattern_id); 
//        $elements = $this->PatternElement->getPatternElements(1); 
        $this->set('elements_list', $elements_list);
    }


    /**
     * edit
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function edit($pattern_element_id = null)
    {

        //不正アクセス
        if (!isset($pattern_element_id)) {
            throw new BadRequestException();
        }

        $pattern_element = $this->PatternElement->getPatternElement($pattern_element_id);
        $pattern_elements = $this->PatternElement->getPatternElements($pattern_element["Pattern"]["id"]);

	for($i = 0; $i < count($pattern_elements); $i++){
		$elements_list[$pattern_elements[$i]["PatternElement"]["id"]] = $pattern_elements[$i]["PatternElement"]["element"];
	}
        $this->set('elements_list', $elements_list);

        //データ構造の加工
        $attribute_count = count($pattern_element['PatternAttribute']);
        for($i = 0; $i < $attribute_count; $i++) {
            $pattern_element['PatternAttribute']['id'][] = $pattern_element['PatternAttribute'][$i]['id'];
            $pattern_element['PatternAttribute']['type'][] = $pattern_element['PatternAttribute'][$i]['type'];
            $pattern_element['PatternAttribute']['name'][] = $pattern_element['PatternAttribute'][$i]['name'];
        }

        //データ構造の加工
        $method_count = count($pattern_element['PatternMethod']);
        for($i = 0; $i < $method_count; $i++) {
            $pattern_element['PatternMethod']['id'][] = $pattern_element['PatternMethod'][$i]['id'];
            $pattern_element['PatternMethod']['type'][] = $pattern_element['PatternMethod'][$i]['type'];
            $pattern_element['PatternMethod']['name'][] = $pattern_element['PatternMethod'][$i]['name'];
        }

//pr($elements_list);
//pr($pattern_elements);

        //データ構造の加工
        $relation_count = count($pattern_element['PatternRelation']);
        for($i = 0; $i < $relation_count; $i++) {

            //現在存在していれば
            // if($this->PatternElement->checkElement($pattern_element['PatternRelation'][$i]['pattern_element_relation_id'])) {
            $pattern_element['PatternRelation']['id'][] = $pattern_element['PatternRelation'][$i]['id'];
            $pattern_element['PatternRelation']['pattern_element_relation_id'][] = $pattern_element['PatternRelation'][$i]['pattern_element_relation_id'];
            // }
        }
        $this->set('pattern_element_id', $pattern_element_id);
        $this->set('pattern_element', $pattern_element);
        $this->set('pattern_elements', $pattern_elements);
        $this->set('attribute_count', $attribute_count);
        $this->set('method_count', $method_count);
        $this->set('relation_count', $relation_count);

        if (!empty($this->request->data['editElement'])) {

            $request_data = $this->request->data;

            // トランザクション処理
            $this->PatternElement->begin();

            $data['PatternElement']['id'] = $pattern_element['PatternElement']['id'];
            $data['PatternElememt']['interface'] = $request_data['PatternElement']['interface'];
            $data['PatternElement']['element'] = $request_data['PatternElement']['element'];

            if (!$this->PatternElement->save($data['PatternElement'],false,array('id','interface','element'))) {
                $this->PatternElement->rollback();
                throw new InternalErrorException();
            }

            $this->PatternElement->commit();

            if(!empty($request_data['PatternAttribute'])) {
                for($i = 0; $i < count($request_data['PatternAttribute']['type']); $i++) {

                    if(!empty($request_data['PatternAttribute']['name'][$i]))  {

                        //初期化
                        $data = array();

                        // // トランザクション処理
                        $this->PatternAttribute->create();
                        $this->PatternAttribute->begin();

                        if(!empty($request_data['PatternAttribute']['id'][$i])) {
                            $data['PatternAttribute']['id'] = $request_data['PatternAttribute']['id'][$i];
                        }
                        $data['PatternAttribute']['type'] = $request_data['PatternAttribute']['type'][$i];
                        $data['PatternAttribute']['name'] = $request_data['PatternAttribute']['name'][$i];
                        $data['PatternAttribute']['pattern_element_id'] = $pattern_element['PatternElement']['id'];

                        if (!$this->PatternAttribute->save($data['PatternAttribute'],false,array('id','type','name','pattern_element_id'))) {
                            $this->PatternAttribute->rollback();
                            throw new InternalErrorException();
                        }
                        $this->PatternAttribute->commit();
                    }
                }
            }

            if(!empty($request_data['PatternMethod'])) {
                for($i = 0; $i < count($request_data['PatternMethod']['type']); $i++) {


                        //初期化
                    $data = array();

                        // // トランザクション処理
                    $this->PatternMethod->create();
                    $this->PatternMethod->begin();

                    if(!empty($request_data['PatternMethod']['id'][$i])) {
                        $data['PatternMethod']['id'] = $request_data['PatternMethod']['id'][$i];
                    }
                    $data['PatternMethod']['type'] = $request_data['PatternMethod']['type'][$i];
                    $data['PatternMethod']['name'] = $request_data['PatternMethod']['name'][$i];
                    $data['PatternMethod']['pattern_element_id'] = $pattern_element['PatternElement']['id'];

                    if (!$this->PatternMethod->save($data['PatternMethod'],false,array('id','type','name','pattern_element_id'))) {
                        $this->PatternMethod->rollback();
                        throw new InternalErrorException();
                    }
                    $this->PatternMethod->commit();
                }
            }

            if(!empty($request_data['PatternRelation'])) {
                for($i = 0; $i < count($request_data['PatternRelation']['pattern_element_relation_id']); $i++) {
                    if(!empty($request_data['PatternRelation']['pattern_element_relation_id'][$i]))  {

                         //初期化
                        $data = array();

                        //トランザクション処理
                        $this->PatternRelation->create();
                        $this->PatternRelation->begin();

                        if(!empty($request_data['PatternRelation']['id'][$i])) {
                            $data['PatternRelation']['id'] = $request_data['PatternRelation']['id'][$i];
                        }
                        $data['PatternRelation']['pattern_element_id'] = $pattern_element['PatternElement']['id'];
                        $data['PatternRelation']['pattern_element_relation_id'] = $request_data['PatternRelation']['pattern_element_relation_id'][$i];

                        if (!$this->PatternRelation->save($data['PatternRelation'],false,array('id','pattern_element_id','pattern_element_relation_id'))) {
                            $this->PatternRelation->rollback();
                            throw new InternalErrorException();
                        }
                        $this->PatternRelation->commit();
                    }
                }
            }
            $pattern_id = $this->Session->read('Pattern.id');
            $this->redirect(array('controller' => 'Patterns', 'action' => 'structure',$pattern_id));


        }
        $this->request->data = $pattern_element;
    }

       /**
     * edit
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
       public function add()
       {

	$user = $this->getUser();

        //エレメントの追加
        if (!empty($this->request->data['addPattern'])) {

            $request_data = $this->request->data;

            // // トランザクション処理
            $this->Pattern->begin();

            $data['Pattern']['name'] = $request_data['Pattern']['name'];
            //$data['Pattern']['pattern_id'] = $this->Session->read('Pattern.id');
            $data['Pattern']['user_id'] = $user['id'];

            //if (!$this->Pattern->save($data['Pattern'],false,array('pattern_name','pattern_id'))) {
            if (!$this->Pattern->save($data['Pattern'],false,array('name','user_id'))) {
                $this->Pattern->rollback();
                throw new InternalErrorException();
            }

            $pattern_id = $this->Pattern->id;

            $this->Pattern->commit();

            $pattern_id = $this->Session->read('Pattern.id');
            $this->redirect(array('controller' => 'Patterns', 'action' => 'structure',$pattern_id));

        }
    }

     /**
     * add_element
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
       public function add_element($pattern_id=null)
       {
	//エレメントの追加
        if (!empty($this->request->data['addElement'])) {

            $request_data = $this->request->data;

            // // トランザクション処理
            $this->PatternElement->begin();

            $data['PatternElement']['interface'] = $request_data['PatternElement']['interface'];
            $data['PatternElement']['element'] = $request_data['PatternElement']['element'];
            $data['PatternElement']['pattern_id'] = h($pattern_id);

            if (!$this->PatternElement->save($data['PatternElement'],false,array('interface','element','pattern_id'))) {
                $this->PatternElement->rollback();
                throw new InternalErrorException();
            }

            $pattern_element_id = $this->PatternElement->id;

            $this->PatternElement->commit();
	for($i = 0; $i < count($request_data['PatternAttribute']['type']); $i++) {

                if(!empty($request_data['PatternAttribute']['name'][$i]))  {

                    //初期化
                    $data = array();

                    // // トランザクション処理
                    $this->PatternAttribute->create();
                    $this->PatternAttribute->begin();

                    $data['PatternAttribute']['type'] = $request_data['PatternAttribute']['type'][$i];
                    $data['PatternAttribute']['name'] = $request_data['PatternAttribute']['name'][$i];
                    $data['PatternAttribute']['pattern_element_id'] = h($pattern_id);

                    if (!$this->PatternAttribute->save($data['PatternAttribute'],false,array('type','name','pattern_id'))) {
                        $this->PatternElement->rollback();
                        $this->PatternAttribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->PatternAttribute->commit();
                }
            }
	for($t = 0; $t < count($request_data['PatternMethod']['name']); $t++) {
                if(!empty($request_data['PatternMethod']['name'][$t]))  {

                    //初期化
                    $data = array();

                    // // トランザクション処理
                    $this->PatternMethod->create();
                    $this->PatternMethod->begin();

                    $data['PatternMethod']['type'] = $request_data['PatternMethod']['type'][$t];
                    $data['PatternMethod']['name'] = $request_data['PatternMethod']['name'][$t];
                    $data['PatternMethod']['pattern_id'] = h($pattern_id);

                    if (!$this->PatternMethod->save($data['Method'],false,array('type','name','pattern_id'))) {
                        $this->PatternMethod->rollback();
                        throw new InternalErrorException();
                    }
                    $this->PatternMethod->commit();
                }
            }

	if(!empty($request_data['Relation']))  {

                for($i = 0; $i < count($request_data['Relation']['id']); $i++) {

                    if(!empty($request_data['Relation']['id'][$i]))  {

                        //初期化
                        $data = array();

                        // // トランザクション処理
                        $this->Relation->create();
                        $this->Relation->begin();

                        $data['Relation']['label_id'] = $label_id;
                        $data['Relation']['label_relation_id'] = $request_data['Relation']['id'][$i];

                        if (!$this->Relation->save($data['Relation'],false,array('label_id','label_relation_id'))) {
                            $this->Relation->rollback();
                            throw new InternalErrorException();
                        }
                        $this->Relation->commit();
                    }
                }
            }

//	$this->redirect(array('controller' => 'Top', 'action' => 'index'));
        $this->redirect(array('controller' => 'Patterns', 'action' => 'structure',$pattern_id));

            return;

	       }
    }


    /* delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function delete($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->Pattern->begin();

        if (!$this->Pattern->delete($id)) {
            $this->Pattern->rollback();
            throw new BadRequestException();
        }

        $this->Pattern->commit();

        $pattern_id = $this->Session->read('Pattern.id');
        $this->redirect(array('controller' => 'Patterns'));
    }


    /* delete_condition
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function delete_condition($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->PatternCondition->begin();

        if (!$this->PatternCondition->delete($id)) {
            $this->PatternCondition->rollback();
            throw new BadRequestException();
        }

        $this->PatternCondition->commit();

        $pattern_id = $this->Session->read('Pattern.id');
        $this->redirect(array('controller' => 'Patterns', 'action' => 'requirements', $pattern_id));
    }


    /* delete_action
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function delete_action($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->PatternAction->begin();

        if (!$this->PatternAction->delete($id)) {
            $this->PatternAction->rollback();
            throw new BadRequestException();
        }

        $this->PatternAction->commit();

        $pattern_id = $this->Session->read('Pattern.id');
        $this->redirect(array('controller' => 'Patterns', 'action' => 'requirements', $pattern_id));
    }

    /* element_delete
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function element_delete($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->PatternElement->begin();

        if (!$this->PatternElement->delete($id)) {
            $this->PatternElement->rollback();
            throw new BadRequestException();
        }

        $this->PatternElement->commit();

        $pattern_id = $this->Session->read('Pattern.id');
        $this->redirect(array('controller' => 'Patterns', 'action' => 'elements',$pattern_id));
    }


    /**
     * ocl
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function ocl($pattern_id = null)
    {

        //不正アクセス
        if (!isset($pattern_id)) {
            throw new BadRequestException();
        }

        $pattern_elements = $this->PatternElement->getPatternElements($pattern_id);
        $pattern_ocl = $this->PatternOcl->getPatternOcls($pattern_id);

//var_dump($pattern_ocl);
//pr($this->request);
//exit();
	for($i = 0; $i < count($pattern_elements); $i++){
		$elements_list[$pattern_elements[$i]["PatternElement"]["id"]] = $pattern_elements[$i]["PatternElement"]["element"];
	}
//        $this->set('elements_list', $elements_list);

        $this->set('pattern_id', $pattern_id);
        $this->set('pattern_elements', $pattern_elements);
        $this->set('pattern_ocl', $pattern_ocl);

        if (!empty($this->request->data['editOCL'])) {

            $request_data = $this->request->data;

            // トランザクション処理
            $this->PatternOcl->begin();

            $data['PatternOcl']['pattern_id'] = $pattern_id;
            $data['PatternOcl']['ocl'] = $request_data['PatternOcl']['ocl'];

	    // OCLが作成されていない場合
	    if(!$pattern_ocl){
		$this->PatternOcl->create();
		$ocl_id = $this->PatternOcl->find("count", array());
		$ocl_id++;
            	$data['PatternOcl']['id'] = $ocl_id;;
	    }else{
            	$data['PatternOcl']['id'] = $pattern_ocl['PatternOcl']['id'];
	    }

            if (!$this->PatternOcl->save($data['PatternOcl'],false,array('id','pattern_id','ocl'))) {
                $this->PatternOcl->rollback();
                throw new InternalErrorException();
            }

            $this->PatternOcl->commit();
            $pattern_id = $this->Session->read('Pattern.id');
            $this->redirect(array('controller' => 'Patterns', 'action' => 'structure',$pattern_id));


        }
    }


    /**
     * requirements
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function requirements($pattern_id = null)
    {

        //不正アクセス
        if (!isset($pattern_id)) {
            throw new BadRequestException();
        }
        //パターン情報を取得
        $pattern_elements = $this->PatternElement->getPatternElements($pattern_id);
	$pattern_conditions = $this->PatternCondition->getPatternConditions($pattern_id);
	$pattern_actions = $this->PatternAction->getPatternActions($pattern_id);
	$checked = "";
	if(is_array($pattern_actions)){
		foreach($pattern_actions as $key => $value){
			if(!empty($value['PatternAction']['checked'])){
				$val = explode(",",$value['PatternAction']['checked']);
				if(is_array($val)){
					foreach($val as $check_number){
						$checked[$key][$check_number] = 1;
					}
				}
			}
		}
	}
        $this->set('pattern_conditions', $pattern_conditions);
        $this->set('pattern_actions', $pattern_actions);
        $this->set('checked', $checked);
        $this->set('pattern_elements', $pattern_elements);
        $this->set('pattern_id', $pattern_id);

/*
        //Method情報を取得
        $security_design_requirement = $this->SecurityDesignRequirement->getSecurityDesignRequirement($method_id);
        $this->set('security_design_requirement', $security_design_requirement);

        $security_design_requirement_count = pow (2, count($security_design_requirement));
        $this->set('security_design_requirement_count', $security_design_requirement_count);

        $td_rowspan = count($security_design_requirement) * 2 + 2;
        $this->set('td_rowspan', $td_rowspan);
*/
    }

     /**
     * add_condition
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
       public function add_condition($pattern_id=null,$requirement_id=null)
       {
	//Conditionの追加
        if (!empty($this->request->data['addCondition'])) {

            $request_data = $this->request->data;

	    // // Condtion
            // // トランザクション処理
            $this->PatternCondition->create();
            $this->PatternCondition->begin();

            $data['PatternCondition']['condition'] = $request_data['PatternCondition']['condition'];
            $data['PatternCondition']['pattern_id'] = $pattern_id;
            if (!$this->PatternCondition->save($data['PatternCondition'],false,array('condition','pattern_id'))) {
                $this->PatternCondition->rollback();
                throw new InternalErrorException();
            }

            $this->PatternCondition->commit();

//	    $pattern_condition_id = $this->PatternCondition->id;

/*
	    // // True Action
            // // トランザクション処理
            $this->PatternRequirementAction->create();
            $this->PatternRequirementAction->begin();

            $data['PatternRequirementAction']['action'] = $request_data['PatternRequirementAction']['action'][0];
            $data['PatternRequirementAction']['condition_type'] = 0;
            $data['PatternRequirementAction']['pattern_id'] = $pattern_id;
            $data['PatternRequirementAction']['pattern_requirement_id'] = $pattern_requirement_id;

            if (!$this->PatternRequirementAction->save($data['PatternRequirementAction'],false,array('action','pattern_id','pattern_requirement_id','condition_type'))) {
                $this->PatternRequirementAction->rollback();
                throw new InternalErrorException();
            }

            $this->PatternRequirementAction->commit();


	    // // False Action
            // // トランザクション処理
            $this->PatternRequirementAction->create();
            $this->PatternRequirementAction->begin();

            $data['PatternRequirementAction']['action'] = $request_data['PatternRequirementAction']['action'][1];
            $data['PatternRequirementAction']['condition_type'] = 1;
            $data['PatternRequirementAction']['pattern_id'] = $pattern_id;
            $data['PatternRequirementAction']['pattern_requirement_id'] = $pattern_requirement_id;

            if (!$this->PatternRequirementAction->save($data['PatternRequirementAction'],false,array('action','pattern_id','pattern_requirement_id','condition_type'))) {
                $this->PatternRequirementAction->rollback();
                throw new InternalErrorException();
            }

            $this->PatternRequirementAction->commit();

*/
            $this->redirect(array('controller' => 'patterns', 'action' => 'requirements',$pattern_id));

            return;

	       }
    }


     /**
     * add_action
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
       public function add_action($pattern_id=null,$requirement_id=null)
       {

        //パターン情報を取得
        $pattern_elements = $this->PatternElement->getPatternElements($pattern_id);
	$pattern_conditions = $this->PatternCondition->getPatternConditions($pattern_id);
	$pattern_actions = $this->PatternAction->getPatternActions($pattern_id);
	$checked = "";
	if(is_array($pattern_actions)){
		foreach($pattern_actions as $key => $value){
			if(!empty($value['PatternAction']['checked'])){
				$val = explode(",",$value['PatternAction']['checked']);
				if(is_array($val)){
					foreach($val as $check_number){
						$checked[$key][$check_number] = 1;
					}
				}
			}
		}
	}
        $this->set('checked', $checked);
        $this->set('pattern_conditions', $pattern_conditions);
        $this->set('pattern_actions', $pattern_actions);
        $this->set('pattern_elements', $pattern_elements);
        $this->set('pattern_id', $pattern_id);

	//Actionの追加
        if (!empty($this->request->data['addAction'])) {

            $request_data = $this->request->data;

	    // チェックボックスの値カンマ区切り
	    $checked = "";
	    foreach($request_data['PatternAction']['checked'] as $key => $value){
		if($value){
			if($checked) $checked .= ",";
			$checked .= $key;
		}
	    }

	    // // Action
            // // トランザクション処理
            $this->PatternAction->create();
            $this->PatternAction->begin();

            $data['PatternAction']['action'] = $request_data['PatternAction']['action'];
            $data['PatternAction']['checked'] = $checked;
            $data['PatternAction']['pattern_id'] = $pattern_id;
            $data['PatternAction']['pattern_requirement_id'] = $pattern_requirement_id;

            if (!$this->PatternAction->save($data['PatternAction'],false,array('action','pattern_id','pattern_requirement_id','checked'))) {
                $this->PatternAction->rollback();
                throw new InternalErrorException();
            }

            $this->PatternAction->commit();

            $this->redirect(array('controller' => 'Patterns', 'action' => 'requirements',$pattern_id));

            return;

	       }
    }

     /**
     * input_xml
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function input_xml ($pattern_id=NULL)
    {
        $pattern_id = $this->Session->read('Pattern.id');
        if (!empty($this->request->data)) {
                $request_data = $this->request->data;
                $xml_address = $request_data["patterns"]["XmlFile"]["tmp_name"];
                App::uses('Xml','Utility');
                $xml = Xml::toArray(Xml::build($xml_address));
                // クラス名など
                $main_xml = $xml["XMI"]["XMI.content"]["UML:Model"]["UML:Namespace.ownedElement"]["UML:Class"];

                // 場所情報,id情報
                $info_xml = $xml["XMI"]["XMI.content"]["XMI.extension"][1]["JUDE:Diagram"]["JUDE:Diagram.presentations"]["JUDE:ClassifierPresentation"];

                // 関連情報
                $rel_xml = $xml["XMI"]["XMI.content"]["XMI.extension"][1]["JUDE:Diagram"]["JUDE:Diagram.presentations"]["JUDE:AssociationPresentation"];

                // 属性情報
                // java.langによる属性情報
                $java_type_xml = $xml["XMI"]["XMI.content"]["UML:Model"]["UML:Namespace.ownedElement"]["UML:Package"]["UML:Namespace.ownedElement"]["UML:Package"][0]["UML:Namespace.ownedElement"]["UML:Class"];
                // それ以外
                $type_xml = $xml["XMI"]["XMI.content"]["UML:Primitive"];

                // ステレオタイプ
                if(array_key_exists("UML:Stereotype",$xml["XMI"]["XMI.content"]["UML:Model"]["UML:Namespace.ownedElement"])){
                        $stre_xml = $xml["XMI"]["XMI.content"]["UML:Model"]["UML:Namespace.ownedElement"]["UML:Stereotype"];
                }

                // 属性ID
                $field_xml = $main_xml[0]["UML:Classifier.feature"]["UML:Attribute"][0]["@name"];

                // ステレオタイプ配列
                if(!empty($stre_xml)){
                        foreach($stre_xml as $s){
                                $stereotype[$s["UML:Stereotype.extendedElement"]["JUDE:ModelElement"]["@xmi.idref"]] = $s["@name"];
                        }
                }

                // 関連付け配列
                if(!empty($rel_xml)){
                        for($i=0;$i<count($rel_xml);$i++){
                                $relations[$i][0]= $rel_xml[$i]["JUDE:UPresentation.servers"]["JUDE:ClassifierPresentation"][0]["@xmi.idref"];
                                $relations[$i][1]= $rel_xml[$i]["JUDE:UPresentation.servers"]["JUDE:ClassifierPresentation"][1]["@xmi.idref"];
                        }
                }

                // // 属性情報配列
                // java.lang
                if(!empty($java_type_xml)){
                        if(!empty($java_type_xml[0])){
                                foreach($java_type_xml as $t){
                                        $types[$t["@xmi.id"]] = $t["@name"];
                                }
                        }else{
                                $types[$java_type_xml["@xmi.id"]] = $java_type_xml["@name"];
                        }
                }

                // それ以外
                if(!empty($type_xml)){
                        if(!empty($type_xml[0])){
                                foreach($type_xml as $t){
                                        $types[$t["@xmi.id"]] = $t["@name"];
                                }
                        }else{
                                $types[$type_xml["@xmi.id"]] = $type_xml["@name"];
                        }
                }
                $typeToId=array(
                        "double" => 0,
                        "Double" => 0,
                        "int" => 0,
                        "Integer" => 0,
                        "float" => 0,
                        "Float" => 0,
                        "short" => 0,
                        "Short" => 0,
                        "string" => 1,
                        "String" => 1,
                        "boolean" => 2,
                        "Boolean" => 2,
                        "data" => 3,
                        "Data" => 3
                );

                $returnvalue=array(
                        "void" => 0,
                        "int" => 1,
                        "float" => 1,
                        "short" => 1,
                        "double" => 1,
                        "String" => 2,
                        "boolean" => 3
                );

                $count = 0;
                $fields=array();
                $methods=array();
                foreach($main_xml as $m){
                        if(!empty($info_xml[$count]["@xmi.id"])){
                                // クラス名
                                $class_name = urldecode($m["@name"]);
                                // ID情報
                                $xid = $info_xml[$count]["@xmi.id"];
                                $main_xid = $main_xml[$count]["@xmi.id"];
                                // フィールド名
                                if(!empty($m["UML:Classifier.feature"]["UML:Attribute"])&&is_array($m["UML:Classifier.feature"]["UML:Attribute"])){
                                        $field_xml = $m["UML:Classifier.feature"]["UML:Attribute"];
                                        if(!empty($field_xml[0])){
                                                for($i=0; $i < count($field_xml);$i++){
                                                        $field_id = $field_xml[$i]["@xmi.id"];
                                                        $field_idref = $field_xml[$i]["UML:StructuralFeature.type"]["UML:Classifier"]["@xmi.idref"];
                                                        $fields[$i]["id"] = $field_id;
                                                        $fields[$i]["name"] = urldecode($field_xml[$i]["@name"]);
                                                        $fields[$i]["type"] = $typeToId[$types[$field_idref]];
                                                }
                                        }else{
                                                        $field_id = $field_xml["@xmi.id"];
                                                        $field_idref = $field_xml["UML:StructuralFeature.type"]["UML:Classifier"]["@xmi.idref"];
                                                        $fields[0]["id"] = $field_id;
                                                        $fields[0]["name"] = urldecode($field_xml["@name"]);
                                                        $fields[0]["type"] = $typeToId[$types[$field_idref]];

                                        }
                                }
                                // メソッド名
                                if(!empty($m["UML:Classifier.feature"]["UML:Operation"])&&is_array($m["UML:Classifier.feature"]["UML:Operation"])){
                                        $method_xml = $m["UML:Classifier.feature"]["UML:Operation"];
                                        if(!empty($method_xml[0])){
                                                for($i=0; $i < count($method_xml); $i++){
                                                        $method_id = $method_xml[$i]["@xmi.id"];
                                                        $method_idref = $method_xml[$i]["UML:BehavioralFeature.parameter"]["UML:Parameter"]["UML:Parameter.type"]["UML:Classifier"]["@xmi.idref"];
                                                        $methods[$i]["id"] = $method_id;
                                                        $methods[$i]["name"] = urldecode($method_xml[$i]["@name"]);
                                                        $methods[$i]["type"] = $returnvalue[$types[$method_idref]];
                                                }
                                        }else{
                                                        $method_id = $method_xml["@xmi.id"];
                                                        $method_idref = $method_xml["UML:BehavioralFeature.parameter"]["UML:Parameter"]["UML:Parameter.type"]["UML:Classifier"]["@xmi.idref"];
                                                        $methods[0]["id"] = $method_id;
                                                        $methods[0]["name"] = urldecode($method_xml["@name"]);
                                                        $methods[0]["type"] = $returnvalue[$types[$method_idref]];

                                        }
                                }

                                // 場所
                                $position_x = $info_xml[$count]["JUDE:JomtPresentation.location"]["XMI.field"][0];
                                $position_y = $info_xml[$count]["JUDE:JomtPresentation.location"]["XMI.field"][1];

                                // トランザクション処理
                                // クラス情報
                                $this->PatternElement->create();
                                $this->PatternElement->begin();

                                $data['PatternElement']['interface'] = empty($stereotype[$main_xid]) ? "" : $stereotype[$main_xid];
                                $data['PatternElement']['element'] = $class_name;
                                $data['PatternElement']['position_x'] = $position_x<=0 ? 0 : $position_x;
                                $data['PatternElement']['position_y'] = $position_y<=0 ? 0 : $position_y;
                                $data['PatternElement']['pattern_id'] = $pattern_id;
                                $data['PatternElement']['is_add'] = 0;

                                if (!$this->PatternElement->save($data['PatternElement'],false,array('interface','element','position_x','position_y','pattern_id'))) {
                                        $this->PatternElement->rollback();
                                        throw new InternalErrorException();
                                }

                                // XMLのクラスIDとデータベースのIDを保存
                                $pattern_element_id = $this->PatternElement->id;
                                $class_info[$xid]= $pattern_element_id;

                                $this->PatternElement->commit();

                                // トランザクション処理
                                // メソッド情報
                                if(!empty($methods)){
                                        foreach($methods as $meth){
                                                $this->PatternMethod->create();
                                                $this->PatternMethod->begin();

                                                $data['PatternMethod']['type'] = $meth["type"];
                                                $data['PatternMethod']['name'] = $meth["name"];
                                                $data['PatternMethod']['pattern_element_id'] = $pattern_element_id;

                                                if (!$this->PatternMethod->save($data['PatternMethod'],false,array('type','name','pattern_element_id'))) {
                                                        $this->PatternMethod->rollback();
                                                        throw new InternalErrorException();
                                                }
                                                $this->PatternMethod->commit();
                                        }
                                }
                                $methods=array();

                                // トランザクション処理
                                // フィールド情報
                                if(!empty($fields)){
                                        foreach($fields as $fiel){
                                                $this->PatternAttribute->create();
                                                $this->PatternAttribute->begin();

                                                $data['PatternAttribute']['type'] = $fiel["type"];
                                                $data['PatternAttribute']['name'] = $fiel["name"];
                                                $data['PatternAttribute']['pattern_element_id'] = $pattern_element_id;

                                                if (!$this->PatternAttribute->save($data['PatternAttribute'],false,array('type','name','pattern_element_id'))) {
                                                        $this->PatternAttribute->rollback();
                                                        throw new InternalErrorException();
                                                }
                                                $this->PatternAttribute->commit();
                                        }
                                }
                                $fields=array();
                                $count++;
                        }
                }


                // 関連付け
                if(!empty($relations)){
                        foreach($relations as $r){
                                $this->PatternRelation->create();
                                $this->PatternRelation->begin();

                                $data['PatternRelation']['pattern_element_id'] = $class_info[$r[0]];
                                $data['PatternRelation']['pattern_element_relation_id'] = $class_info[$r[1]];

                                if (!$this->PatternRelation->save($data['PatternRelation'],false,array('pattern_element_id','pattern_element_relation_id'))) {
                                        $this->PatternRelation->rollback();
                                        throw new InternalErrorException();
                                }
                                $this->PatternRelation->commit();

                        }
                }

            $this->redirect(array('controller' => 'patterns', 'action' => 'structure',$pattern_id));
            return;

        }
    }


}
