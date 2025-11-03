<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
	<!-- Tailwind fallback if Vite is not running -->
	<script>
		(function ensureTailwind(){
			var test = document.createElement('div');
			test.className = 'hidden';
			document.head.appendChild(test);
			var hasViteCss = !!document.querySelector('link[rel="stylesheet"][href*="app.css"]');
			if(!hasViteCss){
				var s = document.createElement('script');
				s.src = 'https://cdn.tailwindcss.com';
				document.head.appendChild(s);
			}
		})();
	</script>
</head>
<body>
    <div>
        @yield('content')
    </div>

    @livewireScripts
	<script>
		document.addEventListener('livewire:init', function () {
			// Prevent hard Livewire errors from blanking the screen
			Livewire.on('error', event => {
				console.error('Livewire Error:', event);
				event.preventDefault();
			});

			// Optional: log when the display updates
			Livewire.on('display-updated', ({ timestamp }) => {
				console.log('Display updated at:', new Date(timestamp * 1000));
			});

			// Subtle loading feedback for long updates
			let loadingTimeout;
			Livewire.hook('commit.prepare', () => {
				loadingTimeout = setTimeout(() => {
					document.body.classList.add('loading');
				}, 500);
			});
			Livewire.hook('commit.finished', () => {
				clearTimeout(loadingTimeout);
				document.body.classList.remove('loading');
			});
		});
	</script>
	<script>
		// Simple clock without Alpine dependency
		(function setupClock(){
			function pad(n){return n.toString().padStart(2,'0');}
			function tick(){
				var now=new Date();
				var h=pad(now.getHours());
				var m=pad(now.getMinutes());
				var s=pad(now.getSeconds());
				var timeStr=h+":"+m+":"+s;
				var dateStr=now.toLocaleDateString();
				var t=document.getElementById('clock-time');
				var d=document.getElementById('clock-date');
				if(t) t.textContent=timeStr;
				if(d) d.textContent=dateStr;
			}
			tick();
			setInterval(tick,1000);
		})();
	</script>
	<style>
		.loading {
			opacity: 0.8;
			transition: opacity 0.3s ease-in-out;
		}
		.loading * {
			pointer-events: none;
		}
		[wire\:loading] {
			display: none;
		}
		[wire\:loading].show {
			display: block;
		}
		[wire\:poll] {
			transition: opacity 0.3s ease-in-out;
		}
	</style>
</body>
</html>