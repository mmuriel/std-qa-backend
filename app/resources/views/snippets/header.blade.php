<header>
	<figure>
		<img src="{{ $slot }}/imgs/SIBA_Logo_120x65.png" style="width: 5rem;" />
	</figure>
	<nav>
		<ul>
			<li>
				<a href="/">Home</a>
			</li>
			<li>
				<a href="/informe">Informes</a>
			</li>
			<li>
				<a href="{{ route('logout') }}" id="logout-link">
                                            Logout
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
				</form>
			</li>
		</ul>
	</nav>
</header>
<script>
	
	$(document).ready(function(){

		$("#logout-link").on("click",function(e){

			e.preventDefault();
			document.getElementById('logout-form').submit();
		})

	});
</script>

