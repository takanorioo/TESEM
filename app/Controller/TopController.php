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
class TopController extends AppController
{
    public $name = 'Top';
    public $uses = array(
        'Label',
        'Relation',
        'Attribute',
        'Method',
        'Countermeasure',
        'Pattern',
        'Project'
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

    /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function index()
    {
      
        //テストの実行
        if (!empty($this->request->data['executeTest'])) {

            $this->layout = false;

            $method_id = $this->request->data['Label']['Method'];
            $label = $this->Method->getLabel($method_id);
            $this->set('label', $label);

            $countermeasures = $this->request->data['Countermeasure'];
            $this->set('countermeasures', $countermeasures);

            $attributes = $this->request->data['Attribute']['name'];
            $attribute_ids = array_keys($attributes);
            for($i = 0; $i < count($attribute_ids); $i++) {
              $attribute_name = $this->Attribute->getAttributeName($attribute_ids[$i]);
              $attributes[$attribute_ids[$i]]['name'] = $attribute_name['Label']['name'];
              $attributes[$attribute_ids[$i]]['attribute_name'] = $attribute_name['Attribute']['name'];
            }
            $this->set('attributes', $attributes);

            $file = "test.txt";
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=$file");
            readfile($file);

            $this->render('use_file');
            return;

         }
    }


    /**
     * input_xml
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
    public function input_xml()
    {
      
        //テストの実行
        if (!empty($this->request->data['executeTest'])) {

            $this->layout = false;

            $method_id = $this->request->data['Label']['Method'];
            $label = $this->Method->getLabel($method_id);
            $this->set('label', $label);

            $countermeasures = $this->request->data['Countermeasure'];
            $this->set('countermeasures', $countermeasures);

            $attributes = $this->request->data['Attribute']['name'];
            $attribute_ids = array_keys($attributes);
            for($i = 0; $i < count($attribute_ids); $i++) {
              $attribute_name = $this->Attribute->getAttributeName($attribute_ids[$i]);
              $attributes[$attribute_ids[$i]]['name'] = $attribute_name['Label']['name'];
              $attributes[$attribute_ids[$i]]['attribute_name'] = $attribute_name['Attribute']['name'];
            }
            $this->set('attributes', $attributes);

            $file = "test.txt";
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=$file");
            readfile($file);

            $this->render('use_file');
            return;

         }
    }
}
