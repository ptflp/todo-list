(function (window) {
	'use strict';
	var li,div,inputC,inputT,label,button,
	tasksList=document.getElementsByClassName('todo-list')[0];
	var tasks = [];
	var action;
	var todoApp = {
		oneTime: 0,
		init: function () { // инициализация движка / engine initialization
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
			console.log('initialization complete');
		},
		initHandlers: function () { // все хэндлеры онклик и ончэндж / on change on click handlers
			this.destroyHandler();
			this.editHandler();
			this.checkHandler();
		},
		getRandomInt: function (min, max) { // генератор псевдо случайных чисел / randomizer
			  return Math.floor(Math.random() * (max - min)) + min;
		},
		removeIf: function () { // вспомогательная функция для array / array helper for removing by condition
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
		clearHandler: function () { // очищение завершенных задач по нажатию / clear tasks on click "Clear completed"
			$('.clear-completed').on('click', function () {
				tasks.removeIf( function(item, idx) {
				    return item.complete == true;
				});
				todoApp.saveData();
			});
		},
		filterHandler: function () { // выделение активной ссылки по нажатию / activate on click link in filter area
			$(window).on('hashchange', function() {
				$('.filters li a').removeClass('selected');
				$(this).addClass('selected');
				let href = $(this).attr('href');
				todoApp.render(href);
				todoApp.initHandlers();
			});
		},
		checkHandler: function () { // обработчик чекбокса / checkbox handler
			$('input.toggle').on('click', function(){
				var liTemp = $(this).closest('li');
				var id = $(this).closest('li').data('id');
				for (var i = 0; i < tasks.length; i++) {
					if (tasks[i].id==id) {
						switch(tasks[i].complete) {
						  case true:  // if (x === 'value1')
						    tasks[i].complete = false;
							todoApp.saveData();
						    break;

						  case false:  // if (x === 'value2')
						    tasks[i].complete = true;
							todoApp.saveData();
						    break;

						  default:
						    console.log('error checkHandler');
						    break;
						}
					}
				}
			});
		},
		editHandler: function () { // обработчик двойного нажатия
			let id;
			$('.todo-list li').dblclick(function(){
				$(this).toggleClass('editing');
				id=$(this).data('id');
			});
			$( "input.edit" ).change(function() {
				let val = $(this).val();
				if(val.length>2){
					for (var i = 0; i < tasks.length; i++) {
						if (tasks[i].id==id) {
							tasks[i].title=val;
							todoApp.saveData();
						}
					}
				}
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
				let id =$(this).closest('li').data('id');
				tasks.removeIf( function(item, idx) {
				    return item.id == id;
				});
				todoApp.saveData();
			});
		},
		getData: function () { // получение данных
			var pathArray = window.location.pathname.split( '/' );
			$.ajax({
			    url: action['get']+pathArray[2],
			    type: "GET",
			    dataType: 'json',
			    xhrFields: {
			         withCredentials: true
			    }
			})
			.done(function(json) {
				if (json) {
					tasks=json.data;
				}
				todoApp.render();
				todoApp.tasksCount();
				todoApp.initHandlers();
			})
			.fail(function() {
				console.log( "error" );
			});
		},
		saveData: function () { // запись данных
			var pathArray = window.location.pathname.split( '/' );
			localStorage.setItem("tasks", JSON.stringify(tasks));
			$.ajax({
			  url: action['save']+pathArray[2],
			  method: "POST",
			  data: { data : JSON.stringify(tasks) },
			  dataType: "html",
			    xhrFields: {
			         withCredentials: true
			    }
			})
			.done(function(json) {
			    todoApp.init();
			})
			.fail(function() {
				console.log( "error" );
			});
		},
		addHandler: function () { // обработчик добавления задачи по нажатию enter /  add task handler on press enter
			$(".new-todo").on('keyup', function (e) {
				let val = $(this).val();
				let collision = todoApp.getRandomInt(111,1000);
			    if (e.keyCode == 13 && val.length>2) {
			    	let id = new Date().getTime() + '-' + collision;
			        let task = {title: val, complete:false, id: id};
			        $(this).val('');
			        tasks.push(task);
			        todoApp.saveData();
			    }
			});
		},
		tasksCount: function () {  // подсчет завершенных задач
			let count = 0;
			for (var i = 0; i < tasks.length; i++) {
				count++;
				if (tasks[i].complete==true) {
					count--;
				}
			}
			$('.todo-count strong').html(count);
		},
		filterActive: function(hash){ // выделение активной ссылки по hash URL
			$('.filters li a').removeClass('selected');
			$('a[href$="'+hash+'"]').addClass('selected');
		},
		render: function (hash=false) { // отрисовка задач
			if (tasks.length>0) {
				let tasksTemp=[];
				if(window.location.hash || hash) {
					if (!hash) {
						hash ='#' + window.location.hash.substring(1);
					}
					switch (hash) { // отрисовка по фильтру
					  case '#/active':   // активные
					  	todoApp.filterActive(hash);
						for (var i = 0; i < tasks.length; i++) {
							if (tasks[i].complete!==true) {
								let item = tasks[i];
								tasksTemp.push(item);
							}
						}
					    break;
					  case '#/completed':   // завершенные
					  	todoApp.filterActive(hash);
						for (var i = 0; i < tasks.length; i++) {
							if (tasks[i].complete==true) {
								let item = tasks[i];
								tasksTemp.push(item);
							}
						}
					    break;
					  default:
					  	todoApp.filterActive('#/');
					    tasksTemp=tasks;
					    break;
					}
				} else {
					tasksTemp=tasks;
				}
				var docfrag = document.createDocumentFragment();
				for (var i = 0; i < tasksTemp.length; i++) { // создание DOM элементов из массива / DOM elements creator from array
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
	$.ajax({
	  url: "/api/",
	  method: "POST",
	  data: { settings : 1},
	  dataType: "json",
	    xhrFields: {
	         withCredentials: true
	    }
	})
	.done(function(json) {
		action=json;
	    todoApp.init();
	})
	.fail(function() {
		console.log( "error" );
	});
	// Your starting point. Enjoy the ride!
})(window);