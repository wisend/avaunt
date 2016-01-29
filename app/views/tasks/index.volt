<div class="row">
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
		<ul class="list-group">
			<li class="list-group-item active">
				<h4>Todo <span class="badge">{{ tasks|length }}</span>
				<button type="button" class="btn btn-default btn-sm pull-right" data-toggle="modal" href='#addtask'><i class="fa fa-icon fa-plus"></i> Add New</button>
				</h4>
			</li>			
			{% for item in tasks %}
				<li class="list-group-item">
					{{ item.description }}
					<span class="pull-right">
						<a href="{{ url('tasks/complete/' ~ item.id ) }}">
							<i class="fa fa-icon fa-times"></i>
						</a>
					</span>
				</li>
			{% endfor %}
		</ul>
	</div>
</div>

<!-- Modal for adding a new task -->
<div class="modal fade" id="addtask">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Task</h4>
			</div>
			<div class="modal-body">
			{{ form("tasks/add") }}
			{% for element in taskForm %}
			{{ element.label() }}
				{{ element }}
			{% endfor %}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save Task</button>
			</div>
		</div>
	</div>
</div>