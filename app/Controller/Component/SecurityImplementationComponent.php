<?php

class SecurityImplementationComponent extends Component {

	 /**
     * getTestName
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function getTestName($testcase)
	{
		preg_match("/(public class )(.*)( {)/", $testcase,$match);
		return $match[2];
	}
	 /**
     * addImport
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function addImport($testcase)
	{
		$import ='
import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
		';
		$testcase = preg_replace("/(package.*)(;)/", $import, $testcase);
		return $testcase;

	}

	/**
     * addTemporaryCallback
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function addTemporaryCallback($matches)
	{

		$temporary = '
			// Get Temporary Method
			String path;
		File file = new File("##TMP_PATH##");
		String getTemporary(String name){

			path = file.getAbsolutePath();

			String tmp=null;
			String data=null;
			try {
				BufferedReader br = new BufferedReader(new FileReader(file));
				tmp = br.readLine();
				String match_true=name+"true";
				String match_false=name+"false";

				while(tmp != null){
					data +=tmp;
					tmp = br.readLine();

				}

				if(data.matches(".*"+match_false+".*")){
					tmp="false";
				}else if(data.matches(".*"+match_true+".*")){
					tmp="true";
				}
				br.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
			return tmp;
		}

		';

		return $temporary.$matches[1];
	}

	/**
     * addTemporary
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function addTemporary($testcase){

		$temporary = '
            // Get Temporary Method
            String path;
        File file = new File("##TMP_PATH##");
        String getTemporary(String name){

            path = file.getAbsolutePath();

            String tmp=null;
            String data=null;
            try {
                BufferedReader br = new BufferedReader(new FileReader(file));
                tmp = br.readLine();
                String match_true=name+"true";
                String match_false=name+"false";

                while(tmp != null){
                    data +=tmp;
                    tmp = br.readLine();

                }

                if(data.matches(".*"+match_false+".*")){
                    tmp="false";
                }else if(data.matches(".*"+match_true+".*")){
                    tmp="true";
                }
                br.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
            return tmp;
        }

        ';

		$testcase = preg_replace ("/(@Before)/", $temporary."@Before", $testcase);
		return $testcase;
	}

	/**
     * insertAssertPointCallback
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function insertAssertPointCallback($matches)
	{

		return $matches[1]."##changemethod##\n".$matches[2].$matches[3];
	}

	/**
     * insertAssertPoint
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function insertAssertPoint($testcase)
	{
		preg_match("/(@Test.*)(})(.*@)/s", $testcase, $matches);
		$replace = $matches[1]."##changemethod##\n".$matches[2].$matches[3];
		$testcase = preg_replace("/(@Test.*)(})(.*@)/s", $replace, $testcase);
		return $testcase;
	}

	/**
     * exchangeAssert
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function exchangeAssert($testcase,$assert)
	{
		$testcase = preg_replace("/##changemethod##/", $assert, $testcase);
		return $testcase;
	}

	/**
     * copyTest
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function copyTest($testcase, &$testcase_count)
	{

		preg_match("/(@Test.*public void )(.*)(\(\) throws Exception {)(.*)(##changemethod##)(.*}.*@After)/s", $testcase, $match);
		$copy_testcase = $match[1].$match[2].$testcase_count.$match[3]."\n\ttry{".$match[4]."\t}catch(Exception e){\n\t}finally{".$match[5]."}".$match[6];
		$copy_testcase = preg_replace("/@After/", "", $copy_testcase);
		$testcase_count++;
		return $copy_testcase;
	}

	/**
     * addTestNumber
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function addTestNumber($testcase, &$testcase_count)
	{
		preg_match("/(.*@Test.*public void )(.*)(\(\) throws Exception {)(.*)(##changemethod##)(.*}.*@After.*)/s", $testcase, $match);
		$testcase = $match[1].$match[2].$testcase_count.$match[3]."\n\ttry{".$match[4]."\t}catch(Exception e){\n\t}finally{".$match[5]."}".$match[6];
		//$testcase = preg_replace("/@After/", "", $testcase,1);
		$testcase_count++;
		return $testcase;
	}

	/**
     * insertTest
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function insertTest($testcase,$addtestcase){

		$testcase = preg_replace("/@After/", $addtestcase."@After", $testcase);

		return $testcase;
	}


	/**
     * createCheckTemporary
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createCheckTemporary($pointcut_name)
	{

		$check =sprintf('
				String %s = getTemporary("%s");
				assertEquals("%s Error", %s, "##changeboolean##");
				',$pointcut_name,$pointcut_name,$pointcut_name,$pointcut_name);

		return $check;

	}

	/**
     * exchangeTmpBoolean
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function exchangeTmpBoolean($assert,$boolean)
	{

		$assert = str_replace("##changeboolean##", $boolean, $assert);

		return $assert;

	}

	/**
     * insertTmpDelete
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function insertTmpDelete($testcase){

		$testcase = preg_replace("/(@After.*throws Exception {)/s", '${1}'."\n\tfile.delete();", $testcase);

		return $testcase;
	}

	/**
     * getTrueField
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function getTrueField($field)
	{

		$true_field=array();
		$count=0;
		while(list($key,$value) = each($field)){

			if($count%2==1){
				$count++;
				continue;
			}
			$true_field[] = $this->inputEscape($field[$count]);
			$count++;

		}

		return $true_field;

	}

	/**
     * getFalseField
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function getFalseField($field)
	{

		$false_field = array();
		$count=0;
		while(list($key,$value) = each($field)){

			if($count%2==0){
				$count++;
				continue;
			}
			$false_field[] = $this->inputEscape($field[$count]);
			$count++;

		}

		return $false_field;

	}

	/**
     * inputEscape
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function inputEscape($input)
	{

		$input = preg_replace("/([\"])/", "\\\"", $input);
		$input = preg_replace("/([\'])/", "\\\'", $input);

		return $input;

	}

	/**
     * createTestCase
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createTestCase($pattern_info,$testcase,$tmp_path)
	{
		$testcase_count=1;
		// テストケース名取得
		$testcase_name = $this->getTestName($testcase);
		// インポート文追加
		$testcase = $this->addImport($testcase);
		// 一時保存関数追加
		$testcase = $this->addTemporary($testcase);
		$testcase = str_replace("##TMP_PATH##", $tmp_path, $testcase);
		// アサーションポイント追加
		$testcase = $this->insertAssertPoint($testcase);
		// コピー用にテスト部分作成
		$testcase_origin = $testcase;
		// テストにナンバリング
		$testcase = $this->addTestNumber($testcase, $testcase_count);
		// アサーション作成(パターンの数繰り返す)
		$pattern_count = count($pattern_info);
		$assert = "";
		for($x=0;$x<$pattern_count;$x++){
			//$assert_tmp = preg_replace('/(\s|　)/',"",$pattern_info[$x]["Pattern"]["name"]);
			$assert_tmp = $pattern_info[$x]["check_temporary"];
			$assert .=  $this->exchangeTmpBoolean($assert_tmp, "true");
		}
		// テストケースにアサーション部分を入れる
		$testcase = $this->exchangeAssert($testcase, $assert);

		// パターンの数繰り返す
		for($i=0;$i<$pattern_count;$i++){

			// テスト部分のコピー
			$copy_test = $this->copyTest($testcase_origin, $testcase_count);

			// true値とfalse値入れ替え
			$copy_test = str_replace($pattern_info[$i]["true_field"],$pattern_info[$i]["false_field"],$copy_test);

			// アサーション作成(パターンの数繰り返す)
			// 初期化
			$assert = "";
			for($x=0;$x<$pattern_count;$x++){
				$assert_tmp = $pattern_info[$x]["check_temporary"];
				//$assert_tmp = $pattern_info[$x]["check_temporary"];
				//$assert_tmp = preg_replace('/(\s|　)/',"",$pattern_info[$x]["Pattern"]["name"]);

				// false値のパターンにはfalse
				if($x==$i){
					$assert .=  $this->exchangeTmpBoolean($assert_tmp, "false");

					// それ以外(true値)のパターンにはtrue
				}else{
					// $assert .=  $this->exchangeTmpBoolean($assert_tmp, "true");
				}
			}

			// テストにアサーション部分を入れる
			$copy_test = $this->exchangeAssert($copy_test, $assert);

			// テストをテストケースに挿入
			$testcase = $this->insertTest($testcase, $copy_test);

		}

		// 一時ファイル削除関数挿入
		$testcase = $this->insertTmpDelete($testcase);

		return $testcase;
	}

	/**
     * inputText
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function inputText($name, $value) {

		$input = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" class=\"form-control\">\n",$name ,htmlspecialchars($value));

		return $input;
	}

	/**
     * inputLoginPatternField
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function inputLoginPatternField($pattern_id, $input_field){

		$input = array();
		$a = $pattern_id;
		if(!empty($input_field[$a])){
			$input[] = "Correct ID:". $this->inputText("field[$a][]", $input_field[$a][0]);
			$input[] = "Incorrect ID:". $this->inputText("field[$a][]", $input_field[$a][1]);
			$input[] = "Correct Password:".$this->inputText("field[$a][]", $input_field[$a][2]);
			$input[] = "Incorrect Password:".$this->inputText("field[$a][]", $input_field[$a][3]);
		}else{
			$input[] = "Correct ID:". $this->inputText("field[$a][]", "");
			$input[] = "Incorrect ID:". $this->inputText("field[$a][]", "");
			$input[] = "Correct Password:".$this->inputText("field[$a][]", "");
			$input[] = "Incorrect Password:".$this->inputText("field[$a][]", "");
		}
		return $input;
	}

	/**
     * inputOnePatternField
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function inputOnePatternField($pattern_id, $input_field){

		$a = $pattern_id;
		if(!empty($input_field)){
			$input[] = "Correct Input:". $this->inputText("field[$a][]", $input_field[$a][0]);
			$input[] = "Incorrect Input:". $this->inputText("field[$a][]", $input_field[$a][1]);
		}else{
			$input[] = "Correct Input:". $this->inputText("field[$a][]", "");
			$input[] = "Incorrect Input:". $this->inputText("field[$a][]", "");
		}
		return $input;
	}

	/**
     * inputPatternField
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function inputPatternField($pattern_id,$input_field){

		$input=array();
		$a = $pattern_id;

		// Role Based Accesee Control
		// Password Design and Use
		if($pattern_id == "1" || $pattern_id == "2"){

			$input = $this->inputLoginPatternField($pattern_id,$input_field);

		// Prevent SQL Injection
		// Prevent Cross-site Scripting
		}else if($pattern_id == "3" || $pattern_id == "4"){

			$input = $this->inputOnePatternField($pattern_id,$input_field);

		// その他
		}else{

			$input = $this->inputOnePatternField($pattern_id,$input_field);
	
		}

		return $input;

	}


	/**
     * createPointcut
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createPointcut($pattern,$pattern_id,$pointcut_name)
	{

//		$m = $pattern_id;
		$pointcut_name = $pointcut_name;
		$class_name = $pattern["class_name"];
		$method_name = $pattern["method_name"];

		$pointcut = sprintf("
				// PointCut
				pointcut %s() :
				call(* *..%s.%s(..));
				",$pointcut_name,$class_name,$method_name);

				return $pointcut;
	}

	/**
     * createAdvice
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createAdvice($pattern,$pattern_id,$pointcut_name)
	{

//		$m = $pattern_id;
//		$pointcut_name = $pointcut_name[$m];

		$advice = sprintf('
				// Advice
				after() returning(Boolean right)  :
				%s() {

				setTemporary("%s",right);
				}
				',$pointcut_name, $pointcut_name);

		return $advice;
	}

	/**
     * createTemporary
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createTemporary ()
	{

		$temporary = '
			// Temporary Method
			void setTemporary(String name,boolean tmp){

				String right = String.valueOf(tmp);
				try{
					File file = new File("##TMP_PATH##");

					if (!file.exists()) {
						file.createNewFile();
					}
					BufferedWriter bw = new BufferedWriter(new FileWriter(file,true));
					bw.write(name+right);
					bw.newLine();
					bw.close();
				}catch(IOException e){
					System.out.println(e);
				}
			}
		';

		return $temporary;
	}

	/**
     * createImport
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createImport ()
	{

		$import = "import static org.junit.Assert.*;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.sql.Connection;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;
import javax.servlet.http.HttpServletResponse;
import org.junit.Rule;
import org.junit.rules.TemporaryFolder;
		";

		return $import;
	}

	/**
     * createHeader
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createHeader ()
	{

		$header = "
			privileged public aspect AspectTest  {
				";

		return $header;
	}

	/**
     * createFooter
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createFooter ()
	{

		$footer = "
		}";

		return $footer;
	}

	/**
     * createAspectj
     * @param:
     * @author: M.Yoshizawa
     * @since: 1.0.0
     */
	function createAspectj($pattern, $tmp_path){
		$aspectj ="";
		$count = count($pattern);
		// AspectJ内容作成
		$aspectj .= $this->createImport();
		$aspectj .= $this->createHeader();
		$aspectj .= $this->createTemporary();
		$pattern["tmp_path"] = str_replace("\\", "\\\\", $tmp_path);
		$aspectj = str_replace("##TMP_PATH##", $tmp_path, $aspectj);
		for($i=0;$i<$count;$i++){
		
			$aspectj .= $this->createPointcut($pattern[$i], $pattern[$i]["pattern_id"], $pattern[$i]["pointcut_name"]);
			$aspectj .= $this->createAdvice($pattern[$i], $pattern[$i]["pattern_id"], $pattern[$i]["pointcut_name"]);

		}
		$aspectj .= $this->createFooter();

		return $aspectj;
	}
}
