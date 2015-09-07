<div>
	<div>
		<div class="row">
			<div style="padding-top: 20px;">
				<span style="font-size: 30px;margin-left: 10px;">Input XML</span>
			</div>
		</div>

		<?php echo $this->Form->create('patterns', array('enctype' => 'multipart/form-data','action' => 'input_xml')); ?>

		<div>
			<div class="row" style = "padding-top:40px">
				<div class="col-md-8 well">
					<?php echo $this->Form->input('XmlFile', array('type'=>'file' )); ?>
				</div>
			</div>
				<div class="col-md-12">
					<p style="text-align: center;">
						<?php
						echo $this->Form->submit('Input XML', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
						?>
					</p>
					<input type="hidden" name="token" value="<?php echo session_id();?>">
					<input type="hidden" name="inputXML" value="inputXML">
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>


