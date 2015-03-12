<?php

/* 確認画面はまだ未完成 */

class BformHelper extends FormHelper {

    /* エラー処理フラグ */
    var $isError = false;

    /* 確認画面使用フラグ */
    var $useConfirm = false;

    /* モデル */
    var $model = null;

    /* コンストラクタ */
    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
		$this->__setError();
	}

    /* フォーム作成 */
    function create($model = null, $options = array()) {
        $out = array();
        $this->model = $model;

        // エラーアラートを追加する
        if($this->isError == true)
        {
            $out[] = "<div class='alert alert-error'>";
            if(isset($options["error_alert"])) {
                $out[] = $options["error_alert"];
            } else {
                $out[] = "入力値が不正です";
            }
            $out[] = "</div>";
        }

        // 確認画面フラグをチェック
        if(isset($options['useConfirm']) && $options['useConfirm'] == true) {
            $this->useConfirm = true;
        }

        // デフォルト値の設定
        if(!isset($options["class"])) {
            if(isset($options['class_type']) && $options['class_type'] == 'inline') {
                $options["class"] = "form-inline";
            } else {
                $options["class"] = "form-horizontal";
            }
        } else {
            if(isset($options['class_type']) && $options['class_type'] == 'inline') {
                $options["class"].= " form-inline";
            } else {
                $options["class"].= " form-horizontal";
            }
        }
        if(!isset($options["inputDefaults"]["div"])) {
            $options["inputDefaults"]["div"] = false;
        }
        if(!isset($options["inputDefaults"]["label"])) {
            $options["inputDefaults"]["label"] = false;
        }
        if(!isset($options["inputDefaults"]["error"])) {
            $options["inputDefaults"]["error"] = array('attributes'=> array('wrap' => 'span', 'class' => 'error help-inline'));
        }
        if(!isset($options["inputDefaults"]["legend"])) {
            $options["inputDefaults"]["legend"] = false;
        }

        // 確認状態フラグを埋め込み
        if($this->useConfirm == true) {
            if($this->__isConfirm() == true) {
                $options = array_merge_recursive($options, array('url' => array('?' => array('BFromMode' => 'finish'))));
            } else {
                $options = array_merge_recursive($options, array('url' => array('?' => array('BFromMode' => 'confirm'))));
            }
        }

        // フォーム作成
        $out[] = parent::create($model, $options);

        return implode("\n", $out);
    }

    function file($fieldName, $options = array()) {
        $out = array();

        // エラー時はクラスを追加
        if($this->error($fieldName)) {
            $out[] = "<div class='control-group error'><div class='controls'>";
        } else {
            $out[] = "<div class='control-group'><div class='controls'>";
        }

        $out[] = parent::file($fieldName, $options);

        if($this->error($fieldName)) {
            $out[] = '<span class="error help-inline">'.$this->error($fieldName, null, array('wrap' => false)).'</span>';
        }


        $out[] = "</div></div>";

        return implode("\n", $out);
    }

    /* インプット作成 */
    function input($fieldName, $options = array()) {
        $out = array();

        // mustがない場合はvalidationから取得
        if(!isset($options['must'])) {
            $options['must'] = $this->__getMustValidation($fieldName);
        }

        // placeholderがない場合はlabelの値を代入
        if(!isset($options['placeholder']) && isset($options['label']))
        {
            $options['placeholder'] = $options["label"];
        }

        // エラー時はクラスを追加
        if($this->error($fieldName)) {
            $out[] = "<div class='control-group error'>";
        } else {
            $out[] = "<div class='control-group'>";
        }

        if(isset($options["label"]) && $options["label"] != false) {
            $out[] = "<label class='control-label'>";
            // 必須表記
            if(isset($options["must"]) && $options["must"] == true) {
                $out[] = "<span class='label label-important'>必須</span>";
                unset($options["must"]);
            }
            // ラベル表示
            $out[] = $options['label'];

            // [?]マークの表示
            if(isset($options["question"]) && $options["question"] != false) {
                $out[] = "<i class='icon-question-sign' rel='tooltip' data-original-title='".$options["question"]."'></i>";
                unset($options["question"]);
            }

            $out[] = '</label>';
            unset($options["label"]);
        }

        $out[] = "<div class='controls'>";

        // 個別設定
        if(isset($options["type"]) && $options["type"] == "radio") {
            if(!isset($options["div"])) {
                $options["div"] = true;
            }
            if(!isset($options["label"])) {
                $options["label"] = true;
            }
        }

        if($this->__isConfirm() == true) {
            // 確認画面
            $out[] = parent::hidden($fieldName);
            $out[] = Set::classicExtract($this->request->data, $fieldName);
        } else {
            // 投稿画面
            $out[] = parent::input($fieldName, $options);
        }

        // ヘルプ表示
        if(isset($options["help"]) && $options["help"] != false) {
            $out[] = "<p class='help-block'>".$options["help"]."</p>";
        }
        if(isset($options["help-in"]) && $options["help-in"] != false) {
            $out[] = "<p class='help-inline'>".$options["help-in"]."</p>";
        }

        $out[] = "</div>";
        $out[] = "</div>";

        return implode("\n", $out);
    }


    /* エラー状態チェック */
    function __setError() {
        if(empty($this->validationErrors)) {
            $this->isError = false;
        }

        $flag = false;
        foreach($this->validationErrors as $m) {
            if(count($m) != 0) {
                $flag = true;
            }
        }
        if($flag == true) {
            $this->isError = true;
        }

    }

    /* 確認状態かチェック */
    function __isConfirm() {
        if($this->useConfirm == false) return false;
        if(!empty($this->request->query['BFromMode']) && ($this->request->query['BFromMode'] == 'confirm')) {
            if($this->isError == false) {
                return true;
            }
        }
        return false;
    }

    /* モデルの取得 */
    function __getModel($model) {
		if (ClassRegistry::isKeySet($model)) {
			$object = ClassRegistry::getObject($model);

		} elseif (isset($this->request->params['models'][$model])) {
			$plugin = $this->request->params['models'][$model]['plugin'];
			$plugin .= ($plugin) ? '.' : null;
			$object = ClassRegistry::init(array(
				'class' => $plugin . $this->request->params['models'][$model]['className'],
				'alias' => $model
			));
		} else {
			$object = ClassRegistry::init($model, true);
		}

        return $object;
    }

    /* 必須判定 */
    function __getMustValidation($fieldName) {
        if(empty($fieldName)) return false;

        $piece = explode('.', $fieldName);
        if(isset($piece[1])) {
            $model = $this->__getModel($piece[0]);
            $field = $piece[1];
        } else {
            $model = $this->model;
            $field = $fieldName;
        }

        if($model && !empty($field)) {
            if(!empty($model->validate[$field])) {
                $validate = $model->validate[$field];
                if(!empty($validate['rule']) && $validate['rule'] == 'notEmpty') {
                    return true;
                }
                if(is_array($validate[0])) {
                    foreach($validate as $vali) {
                        if(!empty($vali['rule']) && $vali['rule'] == 'notEmpty') {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /* シンプルなリンクを作成 */
    function createSimpleLink($text, $url, $class, $confirm) {
        $out = array();
        $out[] = "<form action='{$this->Html->url($url)}' method='POST' style='margin: 0px;'>";
        $out[] = "<input type='submit' value='{$text}' class='{$class}' onClick='return confirm({$confirm})'>";
        $out[] = "</form>";

        return implode("\n", $out);
    }

}