<div class="row content">
	<div class="col-md-12">
		<div class="row wrap-vertical">
			<ul id="nav-tabs" class="nav nav-tabs">
				<li class="active"><a href="#general" data-toggle="tab"><?php echo lang('text_tab_general'); ?></a></li>
				<li><a href="#statuses" data-toggle="tab">Order Status</a></li>
				<li><a href="#features" data-toggle="tab">Payment Features</a></li>
			</ul>
		</div>
		<form role="form" id="edit-form" class="form-horizontal" accept-charset="utf-8" method="POST" action="<?php echo current_url(); ?>">
			<div class="tab-content">
				<div id="general" class="tab-pane row wrap-all active">
					<div class="form-group">
						<label for="input-title" class="col-sm-3 control-label"><?php echo lang('label_title'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="title" id="input-title" class="form-control" value="<?php echo set_value('title', $title); ?>" />
							<?php echo form_error('title', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-merchantID" class="col-sm-3 control-label">Nochex Merchant ID / Email Address</label>
						<div class="col-sm-5">
							<input type="text" name="merchantID" id="input-merchantID" class="form-control" value="<?php echo set_value('merchantID', $merchantID); ?>" />
							<?php echo form_error('merchantID', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>					
					<div class="form-group">
						<label for="input-api-mode" class="col-sm-3 control-label"><?php echo lang('label_api_mode'); ?></label>
						<div class="col-sm-5">
							<div class="btn-group btn-group-switch" data-toggle="buttons">
								<?php if ($api_mode === 'live') { ?>
									<label class="btn btn-warning"><input type="radio" name="api_mode" value="sandbox" <?php echo set_radio('api_mode', 'Test'); ?>>Test</label>
									<label class="btn btn-success active"><input type="radio" name="api_mode" value="live" <?php echo set_radio('api_mode', 'Live', TRUE); ?>>Live</label>
								<?php } else { ?>
									<label class="btn btn-warning active"><input type="radio" name="api_mode" value="sandbox" <?php echo set_radio('api_mode', 'Test', TRUE); ?>>Test</label>
									<label class="btn btn-success"><input type="radio" name="api_mode" value="live" <?php echo set_radio('api_mode', 'Live'); ?>>Live</label>
								<?php } ?>
							</div>
							<?php echo form_error('api_mode', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>	
					
					<div class="form-group">
						<label for="input-status" class="col-sm-3 control-label"><?php echo lang('label_status'); ?></label>
						<div class="col-sm-5">
							<div class="btn-group btn-group-switch" data-toggle="buttons">
								<?php if ($status == '1') { ?>
									<label class="btn btn-danger"><input type="radio" name="status" value="0" <?php echo set_radio('status', '0'); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success active"><input type="radio" name="status" value="1" <?php echo set_radio('status', '1', TRUE); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } else { ?>
									<label class="btn btn-danger active"><input type="radio" name="status" value="0" <?php echo set_radio('status', '0', TRUE); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success"><input type="radio" name="status" value="1" <?php echo set_radio('status', '1'); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } ?>
							</div>
							<?php echo form_error('status', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
				</div>
				
					<div id="statuses" class="tab-pane row wrap-all">
										<div class="form-group">
						<label for="input-order-status" class="col-sm-3 control-label"><?php echo lang('label_order_status'); ?>
							<span class="help-block"><?php echo lang('help_order_status'); ?></span>
						</label>
						<div class="col-sm-5">
							<select name="order_status" id="input-order-status" class="form-control">
								<?php foreach ($statuses as $stat) { ?>
								<?php if ($stat['status_id'] === $order_status) { ?>
									<option value="<?php echo $stat['status_id']; ?>" selected="selected"><?php echo $stat['status_name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $stat['status_id']; ?>"><?php echo $stat['status_name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php echo form_error('order_status', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-paid-order-status" class="col-sm-3 control-label">Payment Received Order Status
						</label>
						<div class="col-sm-5">
							<select name="paid_order_status" id="input-paid-order-status" class="form-control">
								<?php foreach ($statuses as $stat) { ?>
								<?php if ($stat['status_id'] === $paid_order_status) { ?>
									<option value="<?php echo $stat['status_id']; ?>" selected="selected"><?php echo $stat['status_name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $stat['status_id']; ?>"><?php echo $stat['status_name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php echo form_error('paid_order_status', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					</div>
					
					<div id="features" class="tab-pane row wrap-all">
					<div class="form-group">
						<label for="input-hide" class="col-sm-3 control-label">Hide Billing Details</label>
						<div class="col-sm-5">
							<div class="btn-group btn-group-switch" data-toggle="buttons">
								<?php if ($hide == '1') { ?>
									<label class="btn btn-danger"><input type="radio" name="hide" value="0" <?php echo set_radio('hide', '0'); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success active"><input type="radio" name="hide" value="1" <?php echo set_radio('hide', '1', TRUE); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } else { ?>
									<label class="btn btn-danger active"><input type="radio" name="hide" value="0" <?php echo set_radio('hide', '0', TRUE); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success"><input type="radio" name="hide" value="1" <?php echo set_radio('hide', '1'); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } ?>
							</div>
							<?php echo form_error('hide', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					 	
					
					<div class="form-group">
						<label for="input-sepPostage" class="col-sm-3 control-label">Separate Postage</label>
						<div class="col-sm-5">
							<div class="btn-group btn-group-switch" data-toggle="buttons">
								<?php if ($sepPostage == '1') { ?>
									<label class="btn btn-danger"><input type="radio" name="sepPostage" value="0" <?php echo set_radio('sepPostage', '0'); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success active"><input type="radio" name="sepPostage" value="1" <?php echo set_radio('sepPostage', '1', TRUE); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } else { ?>
									<label class="btn btn-danger active"><input type="radio" name="sepPostage" value="0" <?php echo set_radio('sepPostage', '0', TRUE); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success"><input type="radio" name="sepPostage" value="1" <?php echo set_radio('sepPostage', '1'); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } ?>
							</div>
							<?php echo form_error('hide', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>

	
					<div class="form-group">
						<label for="input-ncxdebug" class="col-sm-3 control-label">Debugging</label>
						<div class="col-sm-5">
							<div class="btn-group btn-group-switch" data-toggle="buttons">
								<?php if ($ncxdebug == '1') { ?>
									<label class="btn btn-danger"><input type="radio" name="ncxdebug" value="0" <?php echo set_radio('ncxdebug', '0'); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success active"><input type="radio" name="ncxdebug" value="1" <?php echo set_radio('ncxdebug', '1', TRUE); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } else { ?>
									<label class="btn btn-danger active"><input type="radio" name="ncxdebug" value="0" <?php echo set_radio('ncxdebug', '0', TRUE); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success"><input type="radio" name="ncxdebug" value="1" <?php echo set_radio('ncxdebug', '1'); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } ?>
							</div>
							<?php echo form_error('hide', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					
					
						<div class="form-group">
							<label for="input-order-total" class="col-sm-3 control-label"><?php echo lang('label_order_total'); ?>
								<span class="help-block"><?php echo lang('help_order_total'); ?></span>
							</label>
							<div class="col-sm-5">
								<input type="text" name="order_total" id="input-order-total" class="form-control" value="<?php echo set_value('order_total', $order_total); ?>" />
								<?php echo form_error('order_total', '<span class="text-danger">', '</span>'); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="input-priority" class="col-sm-3 control-label"><?php echo lang('label_priority'); ?></label>
							<div class="col-sm-5">
								<input type="text" name="priority" id="input-priority" class="form-control" value="<?php echo $priority; ?>" />
								<?php echo form_error('priority', '<span class="text-danger">', '</span>'); ?>
							</div>
						</div>
					</div>
			</div>
		</form>
	</div>
</div>
