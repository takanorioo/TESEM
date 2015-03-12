<script type="text/javascript">

	function dimension(w, h) {
		var world = document.getElementById('world');
		world.style.width = w + 'px';
		world.style.height = h + 'px';
	}
</script>



<script>
	function init(){

		dimension(1000, 1000);

		var uml = Joint.dia.uml;
		Joint.paper("world", 5000, 5000);


		<?php for($i = 0; $i < count($elements); $i++): ?>

		var <?php echo h($elements[$i]['Label']['name']);?> = uml.Class.create({
			rect: {x: <?php echo h($elements[$i]['Label']['position_x']);?>, y: <?php echo h($elements[$i]['Label']['position_y']);?>, width: <?php echo h($elements[$i]['width']);?>, height: <?php echo h($elements[$i]['height']);?> , hoge:1 },
			label: "<<<?php echo h($elements[$i]['Label']['interface']);?>>>\n<?php echo h($elements[$i]['Label']['name']);?>",
			swimlane1OffsetY: 30,
			shadow: true,
			attrs: {
				fill: "white"
			},
			labelAttrs: {
				'font-weight': '<?php echo h($elements[$i]['Label']['id']);?>',
			},
			<?php if(!empty($elements[$i]['Attribute'])): ?>
			attributes: [
			<?php for($j = 0; $j < count($elements[$i]['Attribute']); $j++): ?>
			"<?php echo $TYPE[$elements[$i]['Attribute'][$j]['type']];?> : <?php echo h($elements[$i]['Attribute'][$j]['name']);?>",
		<?php endfor; ?>
		],
	<?php endif; ?>

	<?php if(!empty($elements[$i]['Method'])): ?>
	methods: [
	<?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?>
	"<?php echo h($RETURNVALUE[$elements[$i]['Method'][$j]['type']]);?> : <?php echo h($elements[$i]['Method'][$j]['name']);?>",
<?php endfor; ?>
],
<?php endif; ?>

});

<?php endfor; ?>


var all = [
<?php for($i = 0; $i < count($elements); $i++): ?>
	<?php echo h($elements[$i]['Label']['name']);?>,
<?php endfor; ?>
];


<?php for($i = 0; $i < count($elements); $i++): ?>
	<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++): ?>
	<?php echo h($elements[$i]['Label']['name']);?>.joint(<?php echo h($elements[$i]['Relation'][$j]['name']);?>, uml.arrow).register(all);
<?php endfor; ?>
<?php endfor; ?>


}

</script>



<div id="tabcontent">
	<div class="row" style="padding-top: 40px;position: absolute;right: 50px;">
			<input id="set" type="button" name ="set" class ="btn btn-primary" value="Set Layout" style="font-size: 20px;">
	</div>
	<div class="row"style = "padding-top:20px">
		<div id="world"></div>
	</div>
</div>



