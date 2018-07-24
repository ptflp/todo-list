<!DOCTYPE HTML>
<!--
	Phantom by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Todolist py ptflp</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/phantom/assets/css/main.css" />
		<noscript><link rel="stylesheet" href="/phantom/assets/css/noscript.css" /></noscript>
		<style type="text/css">
			.tiles article.style100500 > .image:before {
			    background-color: #014b6f;
			}
		</style>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="inner">

							<!-- Logo -->
								<a href="/" class="logo">
									<span class="symbol"><img src="/phantom/images/logo.svg" alt="" /></span><span class="title">Todolist</span>
								</a>

							<!-- Nav -->
								<nav>
									<ul>
										<li><a href="#menu">Menu</a></li>
									</ul>
								</nav>

						</div>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<h2>Menu</h2>
						<ul>
							<li><a href="">Главная</a></li>
							<li><a href="/user/logout">Logout (<?=$user->email?>)</a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<header>
								<h1>Choose or create your todo list<br /> Look code at <a href="https://github.com/ptflp/todo-list">https://github.com/ptflp/todo-list</a>
							</header>
							<section class="tiles">
								<article class="style2">
									<span class="image">
										<img src="/phantom/images/pic01.jpg" alt="" />
									</span>
									<a href="#" id="createTodolist">
										<h2 style="font-size:12em;letter-spacing:0em;line-height: 0.5;">+</h2>
										<div class="content">
											<p>CREATE TodoList</p>
										</div>
									</a>
								</article>
								<?php
								$todolist=$user->db->getTodolist();
								$i=2;
								foreach ($todolist as $todo):
									$b=0;
									if ($i>12) {
										$i=2;
									}
									if ($i>9){$b='';}
								?>
									<article class="style7">
										<span class="image">
											<img src="/phantom/images/pic<?=$b.$i++?>.jpg" alt="" />
										</span>
										<a href="/todo/<?=$todo->getId();?>">
											<h2><?=$todo->getTitle();?></h2>
										</a>
									</article>
								<?php endforeach; ?>
							</section>
						</div>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Follow</h2>
								<ul class="icons">
									<li><a href="https://www.instagram.com/ptflp/" target="_blank" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="https://github.com/ptflp" target="_blank" class="icon style2 fa-github"><span class="label">GitHub</span></a></li>
									<li><a href="mailto:globallinkliberty@yandex.ru" class="icon style2 fa-envelope-o"><span class="label">Email</span></a></li>
								</ul>
							</section>
							<ul class="copyright">
								<li>&copy; All rights reserved</li><li>Code by: <a href="https://github.com/ptflp">ptflp</a></li>
							</ul>
						</div>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
			<script src="/phantom/assets/js/jquery.min.js"></script>
			<script src="/phantom/assets/js/browser.min.js"></script>
			<script src="/phantom/assets/js/breakpoints.min.js"></script>
			<script src="/phantom/assets/js/util.js"></script>
			<script src="/phantom/assets/js/main.js"></script>

	</body>
</html>