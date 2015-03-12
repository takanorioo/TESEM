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
class SecurityDesignRequirementsController extends AppController
{
    public $name = 'SecurityDesignRequirements';
    public $uses = array(
        'SecurityDesignRequirement',
        'Method',
        'Pattern',
        'PatternElement',
        'PatternBind',
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

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function target($method_id = null)
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

         //パターンのリスト
        $patterns = $this->Pattern->getPatterns();
        for($i = 0; $i < count($patterns); $i++) {
            $pattern_list[$patterns[$i]['Pattern']['id']] = $patterns[$i]['Pattern']['name'];
        }
        $this->set('pattern_list', $pattern_list);

        //SecurityDesignRequirement
        $security_design_requirement = $this->SecurityDesignRequirement->getSecurityDesignRequirement($method_id);
        $this->set('security_design_requirement', $security_design_requirement);

         //TargetFunctionと対策をセット
        if (!empty($this->request->data['selectPattern'])) {

            $request_data = $this->request->data;


            for($i = 0; $i < count($request_data['Pattern']); $i++) {

                // トランザクション処理
                $this->SecurityDesignRequirement->begin();
                $this->SecurityDesignRequirement->create();

                $data['SecurityDesignRequirement']['method_id'] = $method_id;
                $data['SecurityDesignRequirement']['pattern_id'] = $request_data['Pattern'][$i];
                
                if (!$this->SecurityDesignRequirement->save($data['SecurityDesignRequirement'],false,array('method_id','pattern_id'))) {
                    $this->SecurityDesignRequirement->rollback();
                    throw new InternalErrorException();
                }
                $this->SecurityDesignRequirement->commit();
            }
            
            $this->Session->setFlash('You successfully Set Target Function.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'SecurityDesignRequirements', 'action' => 'bind', $method_id));
        }
    }

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function bind($method_id = null)
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

         //パターンのリスト
        $patterns = $this->Pattern->getPatterns();
        for($i = 0; $i < count($patterns); $i++) {
            $pattern_list[$patterns[$i]['Pattern']['id']] = $patterns[$i]['Pattern']['name'];
        }
        $this->set('pattern_list', $pattern_list);

        //SecurityDesignRequirement
        $security_design_requirement = $this->SecurityDesignRequirement->getSecurityDesignRequirement($method_id);
        $this->set('security_design_requirement', $security_design_requirement);

         //TargetFunctionと対策をセット
        if (!empty($this->request->data['selectPattern'])) {

            $request_data = $this->request->data;


            for($i = 0; $i < count($request_data['Pattern']); $i++) {

                // トランザクション処理
                $this->SecurityDesignRequirement->begin();
                $this->SecurityDesignRequirement->create();

                $data['SecurityDesignRequirement']['method_id'] = $method_id;
                $data['SecurityDesignRequirement']['pattern_id'] = $request_data['Pattern'][$i];
                
                if (!$this->SecurityDesignRequirement->save($data['SecurityDesignRequirement'],false,array('method_id','pattern_id'))) {
                    $this->SecurityDesignRequirement->rollback();
                    throw new InternalErrorException();
                }
                $this->SecurityDesignRequirement->commit();
            }
            
            $this->Session->setFlash('You successfully Set Target Function.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'SecurityDesignRequirements', 'action' => 'table', $method_id));
        }

         //TargetFunctionと対策をセット
        if (!empty($this->request->data['setBind'])) {

            $request_data = $this->request->data;


            for($i = 0; $i < count($request_data['PatternElement']); $i++) {

                for($j = 0; $j < count($request_data['PatternElement'][$i]['id']); $j++) {

                        // トランザクション処理
                    $this->PatternBind->begin();
                    $this->PatternBind->create();

                    $data['PatternBind']['security_design_requirement_id'] = $request_data['PatternElement'][$i]['security_design_requirement_id'];
                    $data['PatternBind']['pattern_element_id'] = $request_data['PatternElement'][$i]['pattern_element_id'];
                    $data['PatternBind']['label_id'] = $request_data['PatternElement'][$i]['id'][$j];

                    if (!$this->PatternBind->save($data['PatternBind'],false,array('security_design_requirement_id','pattern_element_id', 'label_id'))) {
                        $this->PatternBind->rollback();
                        throw new InternalErrorException();
                    }
                    $this->PatternBind->commit();
                }
            }
            
            $this->Session->setFlash('You successfully Bind Elements.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'SecurityDesignRequirements', 'action' => 'table', $method_id));
        }

    }


    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function table($method_id = null)
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

        $security_design_requirement_count = pow (2, count($security_design_requirement));
        $this->set('security_design_requirement_count', $security_design_requirement_count);

        $td_rowspan = count($security_design_requirement) * 2 + 2;
        $this->set('td_rowspan', $td_rowspan);

    }
}
