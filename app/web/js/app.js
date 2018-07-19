(function (window) {
	'use strict';
	var tasks = [];
	var li,div,inputC,inputT,label,button;
	var firstTask = {title: 'Test',complete: false};
	var secondTask = {title: 'Test2',complete: true};
	tasks.push(firstTask);
	tasks.push(secondTask);
	var todoApp = {
		render: function (tasksList,tasks) {
			var docfrag = document.createDocumentFragment();
			for (var i = 0; i < tasks.length; i++) {
				li = document.createElement("li");
				div = document.createElement("div");
				div.className = "view";
				inputC = document.createElement("input");
				inputC.className = "toggle";
				inputC.type = "checkbox";

				inputT = document.createElement("input");
				inputT.className = "edit";
				inputT.type = "text";
				inputT.value = tasks[i].title;

				label = document.createElement("label");
				label.textContent=tasks[i].title;

				button = document.createElement("button");
				button.className = "destroy";

				div.appendChild(inputC);
				div.appendChild(label);
				div.appendChild(button);
				li.appendChild(div);
				li.appendChild(inputT);
				docfrag.appendChild(li);
			}
			while (tasksList.firstChild) {
			    tasksList.removeChild(tasksList.firstChild);
			}
			tasksList.appendChild(docfrag);
		}
	}

	var tasksList=document.getElementsByClassName('todo-list');
	tasksList=tasksList[0];
	todoApp.render(tasksList,tasks);

	$('.todo-list li').dblclick(function(){
		$(this).toggleClass('editing');
	});
	$(document).click(function(e) {
	    var target = e.target;

	    if (!$(target).is('input.edit') && !$(target).parents().is('input.edit')) {
	        $('.todo-list li').removeClass('editing');
	    }
	});
	// Your starting point. Enjoy the ride!

})(window);