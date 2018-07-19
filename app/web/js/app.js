(function (window) {
	'use strict';
	var li,div,inputC,inputT,label,button,
	tasksList=document.getElementsByClassName('todo-list')[0];
	var tasks = [];
	var todoApp = {
		oneTime: 0,
		init: function () {
			this.getData();
			this.render();
			this.destroyHandler();
			this.editHandler();
			if (todoApp.oneTime==0) {
				this.inputHandler();
				this.oneTime=1;
				console.log(this.oneTime);
			}
		},
		editHandler: function () { // обработчик двойного нажатия
			$('.todo-list li').dblclick(function(){
				$(this).toggleClass('editing');
			});
		},
		inputHandler: function () { // обработчик снятия фокуса с поля редактирования таска
			$(document).click(function(e) {
			    var target = e.target;
			    if (!$(target).is('input.edit') && !$(target).parents().is('input.edit')) {
			        $('.todo-list li').removeClass('editing');
			    }
			});
		},
		destroyHandler: function () { // обработчик удаления таска
			$('.destroy').on('click', function(){
				var i =$(this).closest('li').index();
				console.log(i);
				tasks.splice(i,1);
				console.log(tasks[i]);
				todoApp.saveData();
				todoApp.init();
			});
		},
		getData: function () { // получение данных
			var json=localStorage.getItem("tasks");
			tasks=JSON.parse(json);
			console.log(tasks);
			if (tasks.length==0) {
				tasks = [{title: 'Test',complete: false},{title: 'Test12',complete: false},{title: 'Test2',complete: false},{title: 'Test43',complete: false},{title: 'Test32',complete: false}];
				var firstTask = {title: 'Test',complete: false};
				var secondTask = {title: 'Test2',complete: true};
				tasks.push(firstTask);
				tasks.push(secondTask);
			}
		},
		saveData: function () { // запись данных
			localStorage.setItem("tasks", JSON.stringify(tasks));
		},
		tasksCount: function () {  //подсчет завершенных задач
			$('.todo-count strong').html(tasks.length);
		},
		render: function () { // отрисовка задач
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
			while (tasksList.firstChild) { // оптимизированный путь удаления child elements
			    tasksList.removeChild(tasksList.firstChild);
			}
			tasksList.appendChild(docfrag); // оптимизированный путь добавления dom через document fragment
		}
	}
	todoApp.init();
	// Your starting point. Enjoy the ride!

})(window);