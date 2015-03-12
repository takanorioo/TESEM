<?php
/**
 * ApiSetLayoutController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
class ApiSetLayoutController extends AppController
{
    public $name = 'ApiSetLayout';
    public $uses = array('Label','PatternElement','Behavior','BehaviorRelations');
    public $autoRender= false;
    public $autoLayout = false;

    /**
     * beforeFilter
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function beforeFilter()
    {
        $this->Auth->allow();
        Configure::write('debug', 0);
        header("Content-Type: application/json; charset=utf-8");
        header("X-Content-Type-Options: nosniff");
    }

    /**
     * setlayout
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function setlayout ()
    {
        $position_x = $this->request->query['x'];
        $position_y = $this->request->query['y'];
        $id = $this->request->query['id'];

        for($i = 0; $i < count($id); $i++) {

            // トランザクション処理
            $this->Label->create();

            $data['Label']['id'] = $id[$i];
            $data['Label']['position_x'] = $position_x[$i];
            $data['Label']['position_y'] = $position_y[$i];

            if (!$this->Label->save($data['Label'],false,array('id','position_x','position_y'))) {
                $this->Label->rollback();
                throw new InternalErrorException();
            }
        }
    }

    /**
     * setlayout
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function setPatternlayout ()
    {
        $position_x = $this->request->query['x'];
        $position_y = $this->request->query['y'];
        $id = $this->request->query['id'];

        for($i = 0; $i < count($id); $i++) {

            // トランザクション処理
            $this->PatternElement->create();

            $data['PatternElement']['id'] = $id[$i];
            $data['PatternElement']['position_x'] = $position_x[$i];
            $data['PatternElement']['position_y'] = $position_y[$i];

            if (!$this->PatternElement->save($data['PatternElement'],false,array('id','position_x','position_y'))) {
                $this->PatternElement->rollback();
                throw new InternalErrorException();
            }
        }
    }

    /**
     * setlayout
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function setBehaviorlayout ()
    {
        $position_x = $this->request->query['x'];
        $position_y = $this->request->query['y'];
        $link_x = $this->request->query['link_x'];
        $link_y = $this->request->query['link_y'];
        $id = $this->request->query['id'];
        $link_id = $this->request->query['link_id'];


        for($i = 0; $i < count($id); $i++) {


            $data = array();

            // トランザクション処理
            $this->Behavior->create();

            $data['Behavior']['id'] = $id[$i];
            $data['Behavior']['position_x'] = $position_x[$i];
            $data['Behavior']['position_y'] = $position_y[$i];

            if (!$this->Behavior->save($data['Behavior'],false,array('id','position_x','position_y'))) {
                $this->Behavior->rollback();
                throw new InternalErrorException();
            }
        }

        for($j = 0; $j < count($link_id); $j++) {

            $data = array();

            // トランザクション処理
            $this->BehaviorRelations->create();

            $data['BehaviorRelations']['id'] = $link_id[$j];
            $data['BehaviorRelations']['position_x'] = $link_x[$j];
            $data['BehaviorRelations']['position_y'] = $link_y[$j];

            if (!$this->BehaviorRelations->save($data['BehaviorRelations'],false,array('id','position_x','position_y'))) {
                $this->BehaviorRelations->rollback();
                throw new InternalErrorException();
            }
        }
    }
}
?>
