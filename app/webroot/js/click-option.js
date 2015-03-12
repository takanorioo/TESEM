
	$(function() {
		var count = 1;
		$("#countermeasure").click(function(){
			$(".countermeasure").append('<tr><td><select name="data[Countermeasure]['+count+']"><option value="">----</option><option value="1">access controll</option><option value="2">I&amp;A</option><option value="2">Input and Data Validation</option></select></td></tr>');
			count ++;
		});
	});

	$(function() {
		var count = 1;
		$("#add_attribute").click(function(){
			$(".add_attribute").append('<tr><td><input name="data[Attribute][type]['+count+']" id="" class="test" placeholder="Type" type="text"/></td><td><input name="data[Attribute][name]['+count+']" id="" class="test" placeholder="AttributeName" type="text"/></td></tr>');
			count ++;
		});
	});
	$(function() {
		var count = 1;
		$("#add_method").click(function(){
			$(".add_method").append('<tr><td><input name="data[Method][type]['+count+']" id="" class="test" placeholder="type" type="text"/></td><td><input name="data[Method][name]['+count+']" id="" class="test" placeholder="MethodName" type="text"/></td></tr>');
			count ++;
		});
	});
	$(function() {
		var option_relation = '<option value="">----</option>';
		<?php for($i = 0; $i < count($option_relation); $i++): ?>
			option_relation  += '<option value=<?php echo h($option_relation[$i]['id']);?>><?php echo h($option_relation[$i]['name']);?></option>'
		<?php endfor; ?>

		var count = 1;
		$("#add_relation").click(function(){
			$(".add_relation").append('<tr><td><select name="data[Relation][id]['+count+']">'+option_relation+'</select></td></tr>');
			count ++;
		});
	});
	$(function() {
		var count = 1;
		$("#selectPatterns").click(function(){
			$(".patterns").append('<div class="row" style = "padding-top:40px"><div class="span10 well"><h1>Role Based Access Pattern</h1><p>Role Based Access Pattern</p></div><div class="span5 well"><div><img src="http://localhost:8888/uml/img/pattern/structure/pattern_1.png" class="img-thumbnail" id="preview" alt="preview" width="400"  /><div style = "padding-top:10px"><p>Role Based Access Pattern Role Based Access PatternRole Based Access PatternRole Based Access PatternRole Based Access PatternRole Based Access PatternRole Based Access Pattern</p></div></div></div><div class="span5 well"><div><img src="http://localhost:8888/uml/img/pattern/behavior/pattern_1.png" id="preview" alt="preview" width="400" /><div style = "padding-top:10px"><p>Role Based Access Pattern Role Based Access PatternRole Based Access PatternRole Based Access PatternRole Based Access PatternRole Based Access PatternRole Based Access Pattern</p></div></div></div></div>');
			count ++;
		});
	});
