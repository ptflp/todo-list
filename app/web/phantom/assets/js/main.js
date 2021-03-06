/*
	Phantom by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
*/

(function($) {

	var	$window = $(window),
		$body = $('body');

	// Breakpoints.
		breakpoints({
			xlarge:   [ '1281px',  '1680px' ],
			large:    [ '981px',   '1280px' ],
			medium:   [ '737px',   '980px'  ],
			small:    [ '481px',   '736px'  ],
			xsmall:   [ '361px',   '480px'  ],
			xxsmall:  [ null,      '360px'  ]
		});

	// Play initial animations on page load.
		$window.on('load', function() {
			window.setTimeout(function() {
				$body.removeClass('is-preload');
			}, 100);
		});

	// Touch?
		if (browser.mobile)
			$body.addClass('is-touch');

	// Forms.
		var $form = $('form');

		// Auto-resizing textareas.
			$form.find('textarea').each(function() {

				var $this = $(this),
					$wrapper = $('<div class="textarea-wrapper"></div>'),
					$submits = $this.find('input[type="submit"]');

				$this
					.wrap($wrapper)
					.attr('rows', 1)
					.css('overflow', 'hidden')
					.css('resize', 'none')
					.on('keydown', function(event) {

						if (event.keyCode == 13
						&&	event.ctrlKey) {

							event.preventDefault();
							event.stopPropagation();

							$(this).blur();

						}

					})
					.on('blur focus', function() {
						$this.val($.trim($this.val()));
					})
					.on('input blur focus --init', function() {

						$wrapper
							.css('height', $this.height());

						$this
							.css('height', 'auto')
							.css('height', $this.prop('scrollHeight') + 'px');

					})
					.on('keyup', function(event) {

						if (event.keyCode == 9)
							$this
								.select();

					})
					.triggerHandler('--init');

				// Fix.
					if (browser.name == 'ie'
					||	browser.mobile)
						$this
							.css('max-height', '10em')
							.css('overflow-y', 'auto');

			});

	// Menu.
		var $menu = $('#menu');

		$menu.wrapInner('<div class="inner"></div>');

		$menu._locked = false;

		$menu._lock = function() {

			if ($menu._locked)
				return false;

			$menu._locked = true;

			window.setTimeout(function() {
				$menu._locked = false;
			}, 350);

			return true;

		};

		$menu._show = function() {

			if ($menu._lock())
				$body.addClass('is-menu-visible');

		};

		$menu._hide = function() {

			if ($menu._lock())
				$body.removeClass('is-menu-visible');

		};

		$menu._toggle = function() {

			if ($menu._lock())
				$body.toggleClass('is-menu-visible');

		};

		$menu
			.appendTo($body)
			.on('click', function(event) {
				event.stopPropagation();
			})
			.on('click', 'a', function(event) {

				var href = $(this).attr('href');

				event.preventDefault();
				event.stopPropagation();

				// Hide.
					$menu._hide();

				// Redirect.
					if (href == '#menu')
						return;

					window.setTimeout(function() {
						window.location.href = href;
					}, 350);

			})
			.append('<a class="close" href="#menu">Close</a>');

		$body
			.on('click', 'a[href="#menu"]', function(event) {

				event.stopPropagation();
				event.preventDefault();

				// Toggle.
					$menu._toggle();

			})
			.on('click', function(event) {

				// Hide.
					$menu._hide();

			})
			.on('keydown', function(event) {

				// Hide on escape.
					if (event.keyCode == 27)
						$menu._hide();

			});




//*
/*
* Additional ptflp code
*
*
*/

		var action;
		var actionID;
 		// Get the modal
		var modal = document.getElementById('myModal');

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];


		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		    modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		    if (event.target == modal) {
		        modal.style.display = "none";
		    }
		}

 		$('form#share-me').on('submit', function (e) {
	        e.preventDefault();
	        var datastring = $(this).serialize();
	        console.log(actionID);
	        $.ajax({
	            type: "POST",
	            url: action["share"]+actionID[1]+'?'+datastring,
	            data: datastring,
	            dataType: "json",
	            success: function(data) {
						modal.style.display = "none";
	                switch(data.success) {
	                  case 0:
	                    swal("Error!", data.error, "error");
	                    break;
	                  case 1:
	                	console.log(data);
	                	var perm;
	                	switch(data.permission){
	                		case "2":
	                			perm="Read/Write";
	                		break;
	                		case "3":
	                			perm="Read";
	                		break;
	                	}
	                    swal("Success!", 'User '+data.email+' added with permission: '+perm+' to: '+ data.title, "success");
	                    break;
	                }
	            },
	            error: function() {
	                swal("Oh noes!", "Please type username! ", "error");
	            }
	        });
 		});
		$.ajax({
		  url: "/api/settings",
		  method: "POST",
		  data: { settings : 1},
		  dataType: "json",
		    xhrFields: {
		         withCredentials: true
		    }
		})
		.done(function(json) {
			console.log(json);
			action=json;
		})
		.fail(function() {
			console.log( "error" );
		});

		$('#createTodolist').on('click',function (e) {
			e.preventDefault();
			swal({
			  text: 'Create Todolist',
			  content: "input",
			  buttons: ["cancel", true],
			})
			.then(title => {
			  if (!title) throw null;

			  return fetch(action['create']+`?title=${title}`, {
			  	credentials: 'include'
			  });
			})
			.then(results => {
				console.log(results);
			  return results.json();
			})
			.then(json => {
				console.log(json.success);
                switch(json.success) {
                  case 0:
                    swal("Error!", data.error, "error");
                    break;
                  case 1:
					swal({
						title: 'Success!',
						text: 'todolist added',
						icon: 'success',
						closeOnClickOutside: false,
						button: false
					});
                    setTimeout(function () {
                        window.location.replace("/");
                        window.location.href = "/";
                    },2500);
                    break;
                }
			})
			.catch(err => {
			  if (err) {
			    swal("Oh noes!", "The AJAX request failed! " + err, "error");
			  } else {
			    swal.stopLoading();
			    swal.close();
			  }
			});
		});

		$('.action li a').on('click',function (e) {
			e.preventDefault();
			actionID = $(this).data('id');
			actionID = actionID.split( '-' );
			switch (actionID[0]) {
			 	case 'remove':
				 	swal({
						title: "Are you sure?",
						text: "Once deleted, you will not be able to recover this todolist!",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
							    url: action["remove"]+actionID[1],
							    type: "GET",
							    dataType: 'json',
							    xhrFields: {
							         withCredentials: true
							    }
							})
							.done(function(json) {
								switch (json.success) {
								 	case 1:
										swal({
											title: 'Success!',
											text: 'Poof! Your todolist has been removed!',
											icon: 'success',
										});
					                    // setTimeout(function () {
					                    //     window.location.replace("/");
					                    //     window.location.href = "/";
					                    // },2500);
					                    $('article#todo-'+actionID[1]).remove();
								 	break;
								}
							})
							.fail(function() {
								swal("Oh noes!", "The AJAX request failed! " + err, "error");
							});
						} else {
							swal("Your todolist safe!");
						}
					});
			 	break;
			 	case 'edit':

					$.ajax({
					    url: action["get"]+actionID[1],
					    type: "GET",
					    dataType: 'json',
					    xhrFields: {
					         withCredentials: true
					    }
					})
					.done(function(json) {
						swal({
						  title: 'Edit title',
						  content: {
						    element: "input",
						    attributes: {
						      type: "text",
						      value: json.title
						    },
						  },
						})
						.then(title => {
						  if (!title) throw null;
							$.ajax({
							    url: action["edit"]+actionID[1]+"?title="+title,
							    type: "GET",
							    dataType: 'json',
							    xhrFields: {
							         withCredentials: true
							    }
							})
							.done(function(json) {
								switch (json.success) {
								 	case 1:
										swal({
											title: 'Success!',
											text: 'Poof! Your todolist title now is ' + json.title,
											icon: 'success',
										});
					                    $('article#todo-'+actionID[1]).find('h2').html(json.title);
								 	break;
								}
							})
							.fail(function(err) {
								swal("Oh noes!", "The AJAX request failed! " + JSON.stringify(err), "error");
							});
						}).catch(swal.noop);
	                    // setTimeout(function () {
	                    //     window.location.replace("/");
	                    //     window.location.href = "/";
	                    // },2500);
					})
					.fail(function() {
						swal("Oh noes!", "The AJAX request failed! " + err, "error");
					});
			 		break;
			 	case 'share':
			 		modal.style.display = "block";
				break;
			 	default:

			 		break;
			}

		});

})(jQuery);