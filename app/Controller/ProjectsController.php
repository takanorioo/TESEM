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
    public $uses = array('Project','UsersProject','User','Label','Attribute','Method','Relation');
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
        $public_projects = $this->Project->getPublicProjects();
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

     /**
     * input_xml
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function input_xml ()
    {
	$project_id = $this->Session->read('Project.id');
	if (!empty($this->request->data)) {
		$request_data = $this->request->data;
		$xml_address = $request_data["Projects"]["XmlFile"]["tmp_name"];
		App::uses('Xml','Utility');
		$xml = Xml::toArray(Xml::build($xml_address));
		// クラス名など
		$main_xml = $xml["XMI"]["XMI.content"]["UML:Model"]["UML:Namespace.ownedElement"]["UML:Class"];
//pr($main_xml);
//exit;

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
				$this->Label->create();
				$this->Label->begin();

				$data['Label']['interface'] = empty($stereotype[$main_xid]) ? "" : $stereotype[$main_xid];
				$data['Label']['name'] = $class_name;
				$data['Label']['position_x'] = $position_x<=0 ? 0 : $position_x;
				$data['Label']['position_y'] = $position_y<=0 ? 0 : $position_y;
				$data['Label']['project_id'] = $project_id;


				if (!$this->Label->save($data['Label'],false,array('interface','name','position_x','position_y','project_id'))) {
					$this->Label->rollback();
					throw new InternalErrorException();
				}

				// XMLのクラスIDとデータベースのIDを保存
				$label_id = $this->Label->id;
				$class_info[$xid]= $label_id;

				$this->Label->commit();

				// トランザクション処理
				// メソッド情報
				if(!empty($methods)){
					foreach($methods as $meth){
						$this->Method->create();
						$this->Method->begin();

						$data['Method']['type'] = $meth["type"];
						$data['Method']['name'] = $meth["name"];
						$data['Method']['label_id'] = $label_id;

						if (!$this->Method->save($data['Method'],false,array('type','name','label_id'))) {
							$this->Method->rollback();
							throw new InternalErrorException();
						}
						$this->Method->commit();
					}
				}
				$methods=null;

				// トランザクション処理
				// フィールド情報
				if(!empty($fields)){
					foreach($fields as $fiel){
						$this->Attribute->create();
						$this->Attribute->begin();

						$data['Attribute']['type'] = $fiel["type"];
						$data['Attribute']['name'] = $fiel["name"];
						$data['Attribute']['label_id'] = $label_id;

						if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
							$this->Attribute->rollback();
							throw new InternalErrorException();
						}
						$this->Attribute->commit();
					}
				}
				$fields=null;
				$count++;
			}
		}

		// 関連付け
		if(!empty($relations)){
			foreach($relations as $r){
				$this->Relation->create();
				$this->Relation->begin();

				$data['Relation']['label_id'] = $class_info[$r[0]];
				$data['Relation']['label_relation_id'] = $class_info[$r[1]];

				if (!$this->Relation->save($data['Relation'],false,array('label_id','label_relation_id'))) {
					$this->Relation->rollback();
					throw new InternalErrorException();
				}
				$this->Relation->commit();

			}
		}

            $this->redirect(array('controller' => 'Top', 'action' => 'index'));
            return;

	}
    }
}
