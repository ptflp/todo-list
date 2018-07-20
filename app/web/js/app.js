(function (window) {
	'use strict';
	var li,div,inputC,inputT,label,button,
	tasksList=document.getElementsByClassName('todo-list')[0];
	var tasks = [];
	var todoApp = {
		oneTime: 0,
		init: function () { // инициализация
			this.getData();
			this.render();
			this.tasksCount();
			this.initHandlers();
			if (todoApp.oneTime==0) {
				this.removeIf();
				this.filterHandler();
				this.clearHandler();
				this.addHandler();
				this.inputHandler();
				this.oneTime=1;
			}
			console.log('initialization');
		},
		initHandlers: function () {
			this.destroyHandler();
			this.editHandler();
			this.checkHandler();
		},
		removeIf: function () {
			Array.prototype.removeIf = function(callback) {
			    var i = 0;
			    while (i < this.length) {
			        if (callback(this[i])) {
			            this.splice(i, 1);
			        }
			        else {
			            ++i;
			        }
			    }
			};
		},
		clearHandler: function () {
			$('.clear-completed').on('click', function () {
				tasks.removeIf( function(item, idx) {
				    return item.complete == true;
				});
				todoApp.saveData();
				todoApp.init();
			});
		},
		filterHandler: function () {
			$('.filters li a').on('click',function () {
				$('.filters li a').removeClass('selected');
				$(this).addClass('selected');
				let href = $(this).attr('href');
				todoApp.render(href);
				todoApp.initHandlers();
			});
		},
		checkHandler: function () {
			$('input.toggle').on('click', function(){
				var liTemp = $(this).closest('li');
				var i = $(this).closest('li').data('id');
				console.log(i);
				console.log(tasks[i]);
				switch(tasks[i].complete) {
				  case true:  // if (x === 'value1')
				    tasks[i].complete = false;
					todoApp.saveData();
					todoApp.tasksCount();
					liTemp.removeClass('completed');
				    break;

				  case false:  // if (x === 'value2')
				    tasks[i].complete = true;
					todoApp.saveData();
					todoApp.tasksCount();
					liTemp.addClass('completed');
				    break;

				  default:
				    console.log('error checkHandler');
				    break;
				}
			});
		},
		editHandler: function () { // обработчик двойного нажатия
			let i;
			$('.todo-list li').dblclick(function(){
				$(this).toggleClass('editing');
				i=$(this).index();
			});
			$( "input.edit" ).change(function() {
				let val = $(this).val();
				tasks[i].title=val;
				todoApp.saveData();
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
				let i =$(this).closest('li').data('id');
				tasks.splice(i,1);
				todoApp.saveData();
				todoApp.init();
			});
		},
		getData: function () { // получение данных
			var json=localStorage.getItem("tasks");
			if (json) {
				tasks=JSON.parse(json);
			}
			if (tasks.length==0) {
				// tasks = [{title: 'Test',complete: false},{title: 'Test12',complete: false},{title: 'Test2',complete: false},{title: 'Test43',complete: false},{title: 'Test32',complete: false}];
				// let firstTask = {title: 'Test',complete: false};
				// let secondTask = {title: 'Test2',complete: true};
				// tasks.push(firstTask);
				// tasks.push(secondTask);
			}
		},
		saveData: function () { // запись данных
			localStorage.setItem("tasks", JSON.stringify(tasks));
		},
		addHandler: function () {
			$(".new-todo").on('keyup', function (e) {
				let val = $(this).val();
			    if (e.keyCode == 13 && val.length>2) {
			        let task = {title: val, complete:false};
			        $(this).val('');
			        tasks.push(task);
			        todoApp.saveData();
			        todoApp.init();
			    }
			});
		},
		tasksCount: function () {  //подсчет завершенных задач
			let count = 0;
			for (var i = 0; i < tasks.length; i++) {
				count++;
				if (tasks[i].complete==true) {
					count--;
				}
			}
			$('.todo-count strong').html(count);
		},
		render: function (hash=false) { // отрисовка задач
			if (tasks.length>0) {
				let tasksTemp=[];
				if(window.location.hash) {
					if (!hash) {
						hash ='#' + window.location.hash.substring(1);
					}
					console.log(hash);
					switch (hash) {
					  case '#/active':
						for (var i = 0; i < tasks.length; i++) {
							if (tasks[i].complete!==true) {
								let item = tasks[i];
								item.id = i;
								tasksTemp.push(item);
							}
						}
					    break;
					  case '#/completed':
						for (var i = 0; i < tasks.length; i++) {
							if (tasks[i].complete==true) {
								let item = tasks[i];
								item.id = i;
								tasksTemp.push(item);
							}
						}
					    //Инструкции, соответствующие ''
					    break;
					  default:
					    tasksTemp=tasks;
					    break;
					}
				} else {
					tasksTemp=tasks;
				}
				var docfrag = document.createDocumentFragment();
				for (var i = 0; i < tasksTemp.length; i++) {
					li = document.createElement("li");
					li.setAttribute("data-id", tasksTemp[i].id);
					div = document.createElement("div");
					div.className = "view";

					inputC = document.createElement("input");
					inputC.className = "toggle";
					inputC.type = "checkbox";

					inputT = document.createElement("input");
					inputT.className = "edit";
					inputT.type = "text";
					inputT.value = tasksTemp[i].title;

					label = document.createElement("label");
					label.textContent=tasksTemp[i].title;

					button = document.createElement("button");
					button.className = "destroy";

					if (tasksTemp[i].complete==true) {
						li.className="completed";
						inputC.checked = true;
					}

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
			} else {
				while (tasksList.firstChild) { // оптимизированный путь удаления child elements
				    tasksList.removeChild(tasksList.firstChild);
				}
			}
		}
	}
	todoApp.init();
	// Your starting point. Enjoy the ride!

})(window);