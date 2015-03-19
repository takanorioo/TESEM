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
        'Pattern',
        'PatternElement',
        'PatternAttribute',
        'PatternMethod',
        'PatternRelation',
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
        $behaviors = $this->PatternBehavior->getBehaviorElement($pattern_id);
        $this->set('behaviors', $behaviors);

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
                $behaviors_data['PatternBehavior']['label_id'][] = $behaviors[$i]['PatternBehavior']['pattern_element_id'];
                $behaviors_data['PatternBehavior']['order'][] = $behaviors[$i]['PatternBehavior']['order'];
                $behaviors_data['PatternBehavior']['name'][] = $behaviors[$i]['PatternElement']['element'];
            }
            $this->set('behaviors_data', $behaviors_data);


            $behavior_action_count = 0;


            for($i = 0; $i < count($behaviors); $i++) {
                for($j = 0; $j < count($behaviors[$i]['PatternBehaviorRelations']); $j++) {  
                    $behaviors_data['PatternBehaviorRelations']['id'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['id'];
                    $behaviors_data['PatternBehaviorRelations']['behavior_id'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['pattern_behavior_id'];
                    $behaviors_data['PatternBehaviorRelations']['action'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['action'];
                    $behaviors_data['PatternBehaviorRelations']['guard'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['guard'];
                    $behaviors_data['PatternBehaviorRelations']['behavior_relation_id'][] = $behaviors[$i]['BehaviorRelations'][$j]['behavior_relation_id'];
                    $behaviors_data['PatternBehaviorRelations']['order'][] = $behaviors[$i]['PatternBehaviorRelations'][$j]['order'];
                    $behavior_action_count ++;
                }
            }

            $this->set('behavior_action_count', $behavior_action_count);
            $this->set('behaviors_data', $behaviors_data);
        }

        $pattern = $this->Pattern->getPattern($pattern_id);
        $this->set('pattern', $pattern);

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
        $this->set('attribute_count', $attribute_count);
        $this->set('method_count', $method_count);
        $this->set('relation_count', $relation_count);


        if (!empty($this->request->data['editElement'])) {

            $request_data = $this->request->data;

            // トランザクション処理
            $this->PatternElement->begin();

            $data['PatternElement']['id'] = $pattern_element['PatternElement']['id'];
            $data['PatternElement']['interface'] = $request_data['PatternElement']['interface'];
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
            $this->redirect(array('controller' => 'Patterns', 'action' => 'detail',$pattern_id));


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

        //エレメントの追加
        if (!empty($this->request->data['addElement'])) {

            $request_data = $this->request->data;

            // // トランザクション処理
            $this->PatternElement->begin();

            $data['PatternElement']['interface'] = $request_data['PatternElement']['interface'];
            $data['PatternElement']['element'] = $request_data['PatternElement']['element'];
            $data['PatternElement']['pattern_id'] = $this->Session->read('Pattern.id');

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
                    $data['PatternAttribute']['pattern_element_id'] = $pattern_element_id;

                    if (!$this->PatternAttribute->save($data['PatternAttribute'],false,array('type','name','pattern_element_id'))) {
                        $this->PatternElement->rollback();
                        $this->PatternAttribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->PatternAttribute->commit();
                }
            }

            for($i = 0; $i < count($request_data['PatternMethod']['type']); $i++) {
                if(!empty($request_data['PatternMethod']['type'][$i]))  {

                    //初期化
                    $data = array();

                        // // トランザクション処理
                    $this->PatternMethod->create();
                    $this->PatternMethod->begin();

                    $data['PatternMethod']['type'] = $request_data['PatternMethod']['type'][$i];
                    $data['PatternMethod']['name'] = $request_data['PatternMethod']['name'][$i];
                    $data['PatternMethod']['pattern_element_id'] = $pattern_element_id;

                    if (!$this->PatternMethod->save($data['PatternMethod'],false,array('type','name','pattern_element_id'))) {
                        $this->PatternMethod->rollback();
                        throw new InternalErrorException();
                    }
                    $this->PatternMethod->commit();
                }
            }
            if(!empty($request_data['PatternRelation']))  {

                for($i = 0; $i < count($request_data['PatternRelation']['id']); $i++) {

                    if(!empty($request_data['PatternRelation']['id'][$i]))  {

                        //初期化
                        $data = array();

                        // // トランザクション処理
                        $this->PatternRelation->create();
                        $this->PatternRelation->begin();
                        
                        $data['PatternRelation']['pattern_element_id'] = $pattern_element_id;
                        $data['PatternRelation']['pattern_element_relation_id'] = $request_data['PatternRelation']['id'][$i];

                        if (!$this->PatternRelation->save($data['PatternRelation'],false,array('pattern_element_id','pattern_element_relation_id'))) {
                            $this->PatternRelation->rollback();
                            throw new InternalErrorException();
                        }
                        $this->PatternRelation->commit();
                    }
                }
            }

            $pattern_id = $this->Session->read('Pattern.id');
            $this->redirect(array('controller' => 'Patterns', 'action' => 'detail',$pattern_id));

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
        $this->PatternElement->begin();

        if (!$this->PatternElement->delete($id)) {
            $this->PatternElement->rollback();
            throw new BadRequestException();
        }

        $this->PatternElement->commit();

        $pattern_id = $this->Session->read('Pattern.id');
        $this->redirect(array('controller' => 'Patterns', 'action' => 'detail',$pattern_id));
    }
}
