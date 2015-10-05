<?php
/**
 * TopController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
App::uses('AppController', 'Controller');
class ElementController extends AppController
{
    public $name = 'Element';
    public $uses = array(
        'Label',
        'Relation',
        'Attribute',
        'Method',
        'Countermeasure',
        'Pattern',
        'PatternElement',
        'Project',
        'SecurityRequirement',
        'SecurityDesignRequirement',
        'SecurityImplementationRequirement',
        'TargetFunction',
        'Behavior',
        'BehaviorRelation'
        );
    public $helpers = array('Html', 'Form');
    public $layout = 'base';
	public $components = array("SecurityImplementation");

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


    /**
     * edit
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function edit($label_id = null)
    {
         //不正アクセス
        if (!isset($label_id)) {
            throw new BadRequestException();
        }

        //要素の取得
        $elemet = $this->Label->getElement($label_id);


        //データ構造の加工
        $attribute_count = count($elemet['Attribute']);
        for($i = 0; $i < $attribute_count; $i++) {
            $elemet['Attribute']['id'][] = $elemet['Attribute'][$i]['id'];
            $elemet['Attribute']['type'][] = $elemet['Attribute'][$i]['type'];
            $elemet['Attribute']['name'][] = $elemet['Attribute'][$i]['name'];
        }

        //データ構造の加工
        $method_count = count($elemet['Method']);
        for($i = 0; $i < $method_count; $i++) {
            $elemet['Method']['id'][] = $elemet['Method'][$i]['id'];
            $elemet['Method']['type'][] = $elemet['Method'][$i]['type'];
            $elemet['Method']['name'][] = $elemet['Method'][$i]['name'];
        }


        //データ構造の加工
        $relation_count = count($elemet['Relation']);
        for($i = 0; $i < $relation_count; $i++) {

            //現在存在していれば
            if($this->Label->checkElement($elemet['Relation'][$i]['label_relation_id'])) {
                $elemet['Relation']['id'][] = $elemet['Relation'][$i]['id'];
                $elemet['Relation']['label_relation_id'][] = $elemet['Relation'][$i]['label_relation_id'];
            }
        }

        $this->set('label_id', $label_id);
        $this->set('elemet', $elemet);
        $this->set('attribute_count', $attribute_count);
        $this->set('method_count', $method_count);
        $this->set('relation_count', $relation_count);

        if (!empty($this->request->data['editElement'])) {


            $request_data = $this->request->data;

            // トランザクション処理
            $this->Label->begin();

            $data['Label']['id'] = $label_id;
            $data['Label']['interface'] = $request_data['Label']['interface'];
            $data['Label']['name'] = $request_data['Label']['name'];
            $data['Label']['project_id'] = $this->Session->read('Project.id');

            if (!$this->Label->save($data['Label'],false,array('id','interface','name','project_id'))) {
                $this->Label->rollback();
                throw new InternalErrorException();
            }

            $this->Label->commit();

            if(!empty($request_data['Attribute'])) {
                for($i = 0; $i < count($request_data['Attribute']['type']); $i++) {

                    if(!empty($request_data['Attribute']['name'][$i]))  {

                        //初期化
                        $data = array();
                        
                        // // トランザクション処理
                        $this->Attribute->create();
                        $this->Attribute->begin();

                        if(!empty($request_data['Attribute']['id'][$i])) {
                            $data['Attribute']['id'] = $request_data['Attribute']['id'][$i];
                        }
                        $data['Attribute']['type'] = $request_data['Attribute']['type'][$i];
                        $data['Attribute']['name'] = $request_data['Attribute']['name'][$i];
                        $data['Attribute']['label_id'] = $label_id;

                        if (!$this->Attribute->save($data['Attribute'],false,array('id','type','name','label_id'))) {
                            $this->Attribute->rollback();
                            throw new InternalErrorException();
                        }
                        $this->Attribute->commit();
                    }
                }
            }

            if(!empty($request_data['Method'])) {
                for($i = 0; $i < count($request_data['Method']['type']); $i++) {


                    //初期化
                    $data = array();

                        // // トランザクション処理
                    $this->Method->create();
                    $this->Method->begin();

                    if(!empty($request_data['Method']['id'][$i])) {
                        $data['Method']['id'] = $request_data['Method']['id'][$i];
                    }
                    $data['Method']['type'] = $request_data['Method']['type'][$i];
                    $data['Method']['name'] = $request_data['Method']['name'][$i];
                    $data['Method']['label_id'] = $label_id;

                    if (!$this->Method->save($data['Method'],false,array('id','type','name','label_id'))) {
                        $this->Method->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Method->commit();
                }
            }

            if(!empty($request_data['Relation'])) {
                for($i = 0; $i < count($request_data['Relation']['label_relation_id']); $i++) {
                    if(!empty($request_data['Relation']['label_relation_id'][$i]))  {

                         //初期化
                        $data = array();

                        //トランザクション処理
                        $this->Relation->create();
                        $this->Relation->begin();

                        if(!empty($request_data['Relation']['id'][$i])) {
                            $data['Relation']['id'] = $request_data['Relation']['id'][$i];
                        }
                        $data['Relation']['label_id'] = $label_id;
                        $data['Relation']['label_relation_id'] = $request_data['Relation']['label_relation_id'][$i];

                        if (!$this->Relation->save($data['Relation'],false,array('id','label_id','label_relation_id'))) {
                            $this->Relation->rollback();
                            throw new InternalErrorException();
                        }
                        $this->Relation->commit();
                    }
                }
            }



            $this->redirect(array('controller' => 'Top', 'action' => 'index'));

            return;

        }

        $this->request->data = $elemet;

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
            $this->Label->begin();

            $data['Label']['interface'] = $request_data['Label']['interface'];
            $data['Label']['name'] = $request_data['Label']['name'];
            $data['Label']['project_id'] = $this->Session->read('Project.id');

            if (!$this->Label->save($data['Label'],false,array('interface','name','project_id'))) {
                $this->Label->rollback();
                throw new InternalErrorException();
            }

            $label_id = $this->Label->id;

            $this->Label->commit();


            for($i = 0; $i < count($request_data['Attribute']['type']); $i++) {

                if(!empty($request_data['Attribute']['name'][$i]))  {

                    //初期化
                    $data = array();

                    // // トランザクション処理
                    $this->Attribute->create();
                    $this->Attribute->begin();
                    
                    $data['Attribute']['type'] = $request_data['Attribute']['type'][$i];
                    $data['Attribute']['name'] = $request_data['Attribute']['name'][$i];
                    $data['Attribute']['label_id'] = $label_id;

                    if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                        $this->Label->rollback();
                        $this->Attribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Attribute->commit();
                }
            }

            for($t = 0; $t < count($request_data['Method']['name']); $t++) {
                if(!empty($request_data['Method']['name'][$t]))  {

                    //初期化
                    $data = array();

                    // // トランザクション処理
                    $this->Method->create();
                    $this->Method->begin();

                    $data['Method']['type'] = $request_data['Method']['type'][$t];
                    $data['Method']['name'] = $request_data['Method']['name'][$t];
                    $data['Method']['label_id'] = $label_id;

                    if (!$this->Method->save($data['Method'],false,array('type','name','label_id'))) {
                        $this->Method->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Method->commit();
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
            $this->redirect(array('controller' => 'Top', 'action' => 'index'));
            return;
        }
    }




    /**
     * delete
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
        $this->Label->begin();

        if (!$this->Label->delete($id)) {
            $this->Label->rollback();
            throw new BadRequestException();
        }

        $this->Label->commit();

        $this->Session->setFlash('You successfully delete element.', 'default', array('class' => 'alert alert-success'));
        $this->redirect(array('controller' => 'Top', 'action' => 'index'));
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function attribute_delete($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }

        // トランザクション処理始め
        $this->Attribute->begin();

        if (!$this->Attribute->delete($id)) {
            $this->Attribute->rollback();
            throw new BadRequestException();
        }

        $this->Attribute->commit();

        $this->Session->setFlash('You successfully delete Attribute.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
    }

     /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
     public function method_delete($id = null)
     {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }

        // トランザクション処理始め
        $this->Method->begin();

        if (!$this->Method->delete($id)) {
            $this->Method->rollback();
            throw new BadRequestException();
        }

        $this->Method->commit();

        $this->Session->setFlash('You successfully delete Method.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
    }

     /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
     public function relation_delete($id = null)
     {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }

        // トランザクション処理始め
        $this->Relation->begin();

        if (!$this->Relation->delete($id)) {
            $this->Relation->rollback();
            throw new BadRequestException();
        }

        $this->Relation->commit();

        $this->Session->setFlash('You successfully delete Relation.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
    }


    /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function target()
    {

        //TargetFunctionと対策をセット
        if (!empty($this->request->data['addTargetFunction'])) {

            $request_data = $this->request->data;

            // トランザクション処理
            $this->TargetFunction->begin();

            $data['TargetFunction']['method_id'] = $request_data['Label']['Method'];
            $data['TargetFunction']['project_id'] = $this->Session->read('Project.id');

            if (!$this->TargetFunction->save($data['TargetFunction'],false,array('method_id','project_id'))) {
                $this->TargetFunction->rollback();
                throw new InternalErrorException();
            }
            $this->TargetFunction->commit();
            
            $this->Session->setFlash('You successfully Set Target Function.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Element', 'action' => 'target_list'));
        }
    }

    /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function target_list()
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //要素の取得
        $target_functions = $this->TargetFunction->getTargetFunctions($project_id);

        $target_methods = array();

         //データの加工
        for($i = 0; $i < count($target_functions); $i++) {

            // メソッドからラベル名を取得
            $label = array();
            $label = $this->Method->getLabel($target_functions[$i]['Method']['id']);

            $target_methods[] = $target_functions[$i]['TargetFunction']['id'];
            $target_methods['id'][] = $target_functions[$i]['Method']['id'];
            $target_methods['name'][] = $label['Label']['name']." : ".$target_functions[$i]['Method']['name'];
        }

        $this->set('target_methods', $target_methods);
    }


    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function import()
    {


        $filename = 'xml/target.xml';

        if (!empty($_FILES)) {
            pr($_FILES);

            if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {

                $movies = simplexml_load_file($_FILES["upfile"]["tmp_name"]);
                pr($movies);


              // if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
              //   chmod("files/" . $_FILES["upfile"]["name"], 0644);
              //   echo $_FILES["upfile"]["name"] . "をアップロードしました。";
              // } else {
              //   echo "ファイルをアップロードできません。";
              // }


            } else {
              echo "ファイルが選択されていません。";
            }
        }


        if (file_exists($filename)) {

            $movies = simplexml_load_file($filename);

            foreach ($movies->XMI->Model->Namespace->Class as $class) {


                echo 'Name:';
                echo $class->attributes()->name;
                echo '<br>';


                foreach ($class->Classifier->Attribute as $attribute) {
                    echo 'Attribute:';
                    echo $attribute->attributes()->name;
                    echo '<br>';
                }

                foreach ($class->Classifier->Operation as $operation) {
                    echo 'Operation:';
                    echo $operation->attributes()->name;
                    echo '<br>';
                }
                
      
            }
            

        } else {
            echo "$filename は存在しません";
        }
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function sr_testcasedata($method_id = null)
    {

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        
        if(!empty($this->request->query['attribute'])) {

            $attribute = $this->request->query['attribute'];


            for($i = 0; $i < count($attribute); $i++) {

                //初期化
                $data = array();
                $attribute_detail = array();

                $attribute_detail = explode(".", $attribute[$i]);

                $attribute_id = $this->Attribute->getAttributeIdByName($attribute_detail[1]);


                if(empty($attribute_id)) {

                    // トランザクション処理
                    $this->Attribute->create();
                    $this->Attribute->begin();

                    $data['Attribute']['type'] = $attribute_detail[2];
                    $data['Attribute']['name'] = $attribute_detail[1];
                    $data['Attribute']['label_id'] = $attribute_detail[0];

                    if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                        $this->Attribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Attribute->commit();
                }
            }
        }
        $this->redirect(array('controller' => 'Element', 'action' => 'sr_testcase', $method_id));
    }

    /**
     * sir_testcasedata
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function sir_testcasedata($method_id = null)
    {
        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        if(!empty($this->request->query['attribute'])) {

            $attribute = $this->request->query['attribute'];

            for($i = 0; $i < count($attribute); $i++) {

                //初期化
                $data = array();
                $attribute_detail = array();

                $attribute_detail = explode(".", $attribute[$i]);

                $attribute_id = $this->Attribute->getAttributeIdByName($attribute_detail[1]);

                if(empty($attribute_id)) {

                    // トランザクション処理
                    $this->Attribute->create();
                    $this->Attribute->begin();


                    $data['Attribute']['type'] = $attribute_detail[2];
                    $data['Attribute']['name'] = $attribute_detail[1];
                    $data['Attribute']['label_id'] = $attribute_detail[0];

                    if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                        $this->Attribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Attribute->commit();
                }
            }
        }
        $this->redirect(array('controller' => 'Element', 'action' => 'sir_testcase', $method_id));
    }

    /**
     * sir_testcase
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
         */
    public function sir_testcase($method_id = null) 
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        //Method情報を取得
        $security_design_requirement = $this->SecurityDesignRequirement->getSecurityDesignRequirement($method_id);
        $this->set('security_design_requirement', $security_design_requirement);
        $non_factor = array();
        $input_field = array();
		$sdr_count = count($security_design_requirement);
        for($t = 0; $t < $sdr_count; $t++) {
			$test_case_value = !empty($this->request->data['field']) ? $this->request->data['field'] : "";
            if($security_design_requirement[$t]['Pattern']['id'] == 1) {
                $non_factor[] = $security_design_requirement[$t]['PatternBind'][4]['Label']['id'];
            }
            if($security_design_requirement[$t]['Pattern']['id'] == 2) {
                $non_factor[] = $security_design_requirement[$t]['PatternBind'][1]['Label']['id'];
            }
			$input_field[] = $this->SecurityImplementation->inputPatternField($security_design_requirement[$t]['Pattern']['id'], $test_case_value);

			$key_element[] = $this->PatternElement->getKeyPatternElement($security_design_requirement[$t]['Pattern']['id']);

        }
        $this->set('non_factor', $non_factor);
        $this->set('input_field', $input_field);
        $this->set('sdr_count', $sdr_count);

        $security_design_requirement_count = pow (2, count($security_design_requirement));
        $this->set('security_design_requirement_count', $security_design_requirement_count);


        $td_rowspan = count($security_design_requirement) * 2 + 2;
        $this->set('td_rowspan', $td_rowspan);


        //テストの実行
        if (!empty($this->request->data['executeTest'])&&!empty($this->request->data['Element']['Input Selenium Test Case']['tmp_name'])) {

            $label = $this->Method->getLabel($method_id);
            $this->set('label', $label);

			$tmp_path = $this->request->data['Temp']['path'];
			$tmp_name = $this->request->data['Element']['Input Selenium Test Case']['tmp_name'];
			$input_testcase = file_get_contents($tmp_name);
			for($i=0;$i<$sdr_count;$i++){
				$bind_count[] = $security_design_requirement[$i]['PatternBind'];
			}

			for($i=0;$i<$sdr_count;$i++){

				$pattern_id = $security_design_requirement[$i]['Pattern']['id'];
				$pattern_info[$i]["pattern_id"] = $pattern_id;
				$pattern_info[$i]["check_temporary"] = $this->SecurityImplementation->createCheckTemporary(preg_replace('/(\s|　)/',"",$security_design_requirement[$i]["Pattern"]["name"]));
				$pattern_info[$i]["pointcut_name"] = preg_replace('/(\s|　)/',"",$security_design_requirement[$i]["Pattern"]["name"]);
				// セキュリティパターンでスター(仮)フラグを持ったメソッドのクラス
				$pattern_info[$i]["class_name"] = $key_element[$i]['PatternElement']['element'];
				// セキュリティパターンでスター(仮)フラグを持ったメソッド
				$pattern_info[$i]["method_name"] = $key_element[$i]['PatternMethod']['name'];
				$pattern_info[$i]["true_field"] = $this->SecurityImplementation->getTrueField($test_case_value[$pattern_id]);
				$pattern_info[$i]["false_field"] = $this->SecurityImplementation->getFalseField($test_case_value[$pattern_id]);
				$pattern_info[$i]["Pattern"]["name"] = $security_design_requirement[$i]['Pattern']['name'];

			}


			$output_testcase = $this->SecurityImplementation->createTestCase($pattern_info,$input_testcase,$tmp_path);
			$output_aspectj = $this->SecurityImplementation->createAspectj($pattern_info, $tmp_path);
            $this->set('output_testcase', $output_testcase);
            $this->set('output_aspectj', $output_aspectj);
            $this->render('sir_testscript');
        }
    }



    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function sdr_testcasedata($method_id = null)
    {

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        if(!empty($this->request->query['attribute'])) {

            $attribute = $this->request->query['attribute'];

            for($i = 0; $i < count($attribute); $i++) {

                //初期化
                $data = array();
                $attribute_detail = array();

                $attribute_detail = explode(".", $attribute[$i]);

                $attribute_id = $this->Attribute->getAttributeIdByName($attribute_detail[1]);

                if(empty($attribute_id)) {

                    // トランザクション処理
                    $this->Attribute->create();
                    $this->Attribute->begin();


                    $data['Attribute']['type'] = $attribute_detail[2];
                    $data['Attribute']['name'] = $attribute_detail[1];
                    $data['Attribute']['label_id'] = $attribute_detail[0];

                    if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                        $this->Attribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Attribute->commit();
                }
            }
        }        
        $this->redirect(array('controller' => 'Element', 'action' => 'sdr_testcase', $method_id));
    }

    

     /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
     public function sr_testcase($method_id = null)
     {
               //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        // SecurityRequirement
        $security_requirement = $this->SecurityRequirement->getSecurityRequirement($method_id);

        $security_requirement_count = pow (2, count($security_requirement));

        $this->set('security_requirement', $security_requirement);
        $this->set('security_requirement_count', $security_requirement_count);


        //テストの実行
        if (!empty($this->request->data['executeTest'])) {


            $label = $this->Method->getLabel($method_id);

            $this->set('label', $label);

            $attributes = $this->request->data['Attribute']['name'];

            $attribute_ids = array_keys($attributes);
            for($i = 0; $i < count($attribute_ids); $i++) {
                  $attribute_name = $this->Attribute->getAttributeName($attribute_ids[$i]);
                  $attributes[$attribute_ids[$i]]['name'] = $attribute_name['Label']['name'];
                  $attributes[$attribute_ids[$i]]['attribute_name'] = $attribute_name['Attribute']['name'];
            }
            $this->set('attributes', $attributes);

            $this->render('sr_testscript');
        }
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
         */
    public function sdr_testcase($method_id = null) 
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        //Method情報を取得
        $security_design_requirement = $this->SecurityDesignRequirement->getSecurityDesignRequirement($method_id);
        $this->set('security_design_requirement', $security_design_requirement);

        $non_factor = array();
        for($t = 0; $t < count($security_design_requirement); $t++) {
            if($security_design_requirement[$t]['Pattern']['id'] == 1) {
                $non_factor[] = $security_design_requirement[$t]['PatternBind'][4]['Label']['id'];
            }
            if($security_design_requirement[$t]['Pattern']['id'] == 2) {
                $non_factor[] = $security_design_requirement[$t]['PatternBind'][1]['Label']['id'];
            }
        }
        $this->set('non_factor', $non_factor);

        $security_design_requirement_count = pow (2, count($security_design_requirement));
        $this->set('security_design_requirement_count', $security_design_requirement_count);


        $td_rowspan = count($security_design_requirement) * 2 + 2;
        $this->set('td_rowspan', $td_rowspan);


        //テストの実行
        if (!empty($this->request->data['executeTest'])) {

            $label = $this->Method->getLabel($method_id);
            $this->set('label', $label);

            $attributes = $this->request->data['Attribute']['name'];

            $attribute_ids = array_keys($attributes);
            for($i = 0; $i < count($attribute_ids); $i++) {
                $attribute_name = $this->Attribute->getAttributeName($attribute_ids[$i]);
                $attributes[$attribute_ids[$i]]['name'] = $attribute_name['Label']['name'];
                $attributes[$attribute_ids[$i]]['attribute_name'] = $attribute_name['Attribute']['name'];
            }
            $this->set('attributes', $attributes);
            $this->render('sdr_testscript');
        }
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function sr_model_script_data($method_id = null)
    {

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }
        
        if(!empty($this->request->query['attribute'])) {

            $attribute = $this->request->query['attribute'];

            for($i = 0; $i < count($attribute); $i++) {

                //初期化
                $data = array();
                $attribute_detail = array();

                $attribute_detail = explode(".", $attribute[$i]);

                $attribute_id = $this->Attribute->getAttributeIdByName($attribute_detail[1]);

                if(empty($attribute_id)) {

                    // トランザクション処理
                    $this->Attribute->create();
                    $this->Attribute->begin();

                    $data['Attribute']['type'] = $attribute_detail[2];
                    $data['Attribute']['name'] = $attribute_detail[1];
                    $data['Attribute']['label_id'] = $attribute_detail[0];

                    if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                        $this->Attribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Attribute->commit();
                }
            }
        }        
        $this->redirect(array('controller' => 'Element', 'action' => 'sr_model_script', $method_id));
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function sdr_model_script_data($method_id = null)
    {

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }
        
        if(!empty($this->request->query['attribute'])) {

            $attribute = $this->request->query['attribute'];

            for($i = 0; $i < count($attribute); $i++) {

                //初期化
                $data = array();
                $attribute_detail = array();

                $attribute_detail = explode(".", $attribute[$i]);

                $attribute_id = $this->Attribute->getAttributeIdByName($attribute_detail[1]);

                if(empty($attribute_id)) {

                    // トランザクション処理
                    $this->Attribute->create();
                    $this->Attribute->begin();

                    $data['Attribute']['type'] = $attribute_detail[2];
                    $data['Attribute']['name'] = $attribute_detail[1];
                    $data['Attribute']['label_id'] = $attribute_detail[0];

                    if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                        $this->Attribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Attribute->commit();
                }
            }
        }        
        $this->redirect(array('controller' => 'Element', 'action' => 'sdr_model_script', $method_id));
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
         */
    public function sr_model_script($method_id = null) 
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        // SecurityRequirement
        $security_requirement = $this->SecurityRequirement->getSecurityRequirement($method_id);
        $this->set('security_requirement', $security_requirement);

        $security_requirement_count = count($security_requirement);
        $this->set('security_requirement_count', $security_requirement_count);

        $label = $this->Method->getLabel($method_id);
        $this->set('label', $label);

        $behavior_uis = $this->Behavior->getBehaviorUIs($method_id);
        $this->set('behavior_uis', $behavior_uis);

        $behavior_actor = $this->Behavior->getBehaviorActor($method_id);
        $this->set('behavior_actor', $behavior_actor);

        $this->render('sr_model_script');
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
         */
    public function sdr_model_script($method_id = null) 
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        // SecurityRequirement
        $security_requirement = $this->SecurityRequirement->getSecurityRequirement($method_id);
        $this->set('security_requirement', $security_requirement);

        $security_requirement_count = count($security_requirement);
        $this->set('security_requirement_count', $security_requirement_count);

        $label = $this->Method->getLabel($method_id);
        $this->set('label', $label);

        $behavior_uis = $this->Behavior->getBehaviorUIs($method_id);
        $this->set('behavior_uis', $behavior_uis);

        $behavior_actor = $this->Behavior->getBehaviorActor($method_id);
        $this->set('behavior_actor', $behavior_actor);

        //Method情報を取得
        $security_design_requirement = $this->SecurityDesignRequirement->getSecurityDesignRequirement($method_id);
        $this->set('security_design_requirement', $security_design_requirement);

        $this->render('sdr_model_script');
    }
}
