(function() {
	"use strict";

	function Toast(options) {
		var position;
		this.timeout_id = null;
		this.duration = 3000;
		this.content = '';
		this.position = 'bottom';
		this.pointer = false;

		if (!options || typeof options != 'object') {
			return false;
		}

		if (options.duration) {
			this.duration = parseFloat(options.duration);
		}
		if (options.content) {
			this.content = options.content;
		}

		if (options.pointer) {
			this.pointer = options.pointer;
		}

		if (options.position) {
			position = options.position.toLowerCase();
			if (position === 'top' || position === 'bottom') {
				this.position = position;
			} else {
				this.position = 'bottom';
			}
		}
		this.show();
	}


	Toast.prototype.show = function() {
		if (!this.content) {
			return false;
		}
		clearTimeout(this.timeout_id);

		var body = document.getElementsByTagName('body')[0];

		var previous_toast = document.getElementById('toast_container');
		if (previous_toast) {
			body.removeChild(previous_toast);
		}

		var classes = 'toast_fadein';
		if (this.position === 'top') {
			classes = 'toast_fadein toast_top';
		}

		if (this.pointer) {
			classes = classes + ' event';
		}

		var toast_container = document.createElement('div');
		toast_container.setAttribute('id', 'toast_container');
		toast_container.setAttribute('class', classes);
		body.appendChild(toast_container);

		var toast = document.createElement('div');
		toast.setAttribute('id', 'toast');
		toast.innerHTML = this.content;
		toast_container.appendChild(toast);

		this.timeout_id = setTimeout(this.hide, this.duration);
		return true;
	};

	Toast.prototype.hide = function() {
		var toast_container = document.getElementById('toast_container');

		if (!toast_container) {
			return false;
		}

		clearTimeout(this.timeout_id);

		toast_container.className += ' toast_fadeout';

		function remove_toast() {
			var toast_container = document.getElementById('toast_container');
			if (!toast_container) {
				return false;
			}
			toast_container.parentNode.removeChild(toast_container);
		}

		toast_container.addEventListener('webkitAnimationEnd', remove_toast);
		toast_container.addEventListener('animationEnd', remove_toast);
		toast_container.addEventListener('msAnimationEnd', remove_toast);
		toast_container.addEventListener('oAnimationEnd', remove_toast);

		return true;
	};

	window.Toast = Toast;

})();

var fetchBody = function( url ) {
	if (url.indexOf('#') > -1) {
		return;
	}
	fetch( url ).then(function (response) {
		return response.text();
	}).then(function (html) {
		var title = html.replace(/^.*?<title>(.*?)<\/title>.*?$/s,"$1");
		var body = html.replace(/^.*?<body>(.*?)<\/body>.*?$/s,"$1");
		document.title = title;
		document.body.innerHTML = body;
		history.pushState({page:url}, null, url);
		if (getURLP('position') != null){
			position = getURLP('position');
		}else{
			position = 0;
		}
		console.log(position);
		window.scrollTo({ top: position, behavior: 'smooth' });
		running();
	}).catch(function (err) {
		new Toast({
			content: 'Tizimga ulanishda xatolik'
		});
	});
}

var getURLP = function( name ) {
	var _locSearch = location.search;
    var _splitted = (new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(_locSearch)||[,""]);
    var searchString = _splitted[1].replace(/\+/g, '%20');
    try
    	{
        	searchString = decodeURIComponent(searchString);
        }
    catch(e)
    	{
        	searchString = unescape(searchString);
        }
    return searchString || null;
}  

window.addEventListener('popstate', (event) => {
	fetchBody( location.href );
});

window.addEventListener('scroll', function() {
	var match = document.location.pathname.match(/\.([0-9a-z]+)(?:[\?#]|$)/i);
	if (match != null && ( match[1] == 'html' || match[1] == 'htm' ) ) {
		var position = document.querySelector('html').scrollTop;
		savedPosition = {};
		savedPosition.page = document.location.pathname;
		savedPosition.position = position;
		localStorage.setItem('saved_position', JSON.stringify(savedPosition));
	}
});

function running() {
	if (document.location.pathname == '/' && localStorage.getItem('saved_position') != null ) {
		let savedPosition = JSON.parse( localStorage.getItem('saved_position') );
		if (savedPosition.position > 50) {
			goPage = document.location.origin + savedPosition.page + '?position='+savedPosition.position;
			new Toast({
				content: '<a href="'+goPage+'">Mutolaani avval to‘xtagan joydan davom ettirish</a>',
				duration: 8000,
				pointer:true
			});
		}
	}
	var Anchors = document.getElementsByTagName("a");
	for (var i = 0; i < Anchors.length ; i++) {
		let hostname = new URL(Anchors[i].href);
		if (hostname.host == window.location.host && Anchors[i].href.indexOf('#') < 0) {
			Anchors[i].addEventListener("click", function (event) {
				fetchBody(this.href);
				event.preventDefault();
			}, false);
		}
	}
	if (document.getElementById('searchform') != null) {
		document.getElementById('searchform').addEventListener('submit', function(event) {
			let term = document.getElementById('searchterm').value;
			let url = '/search?term=' + encodeURI(term);
			fetchBody( url );
			event.preventDefault();
		});
	}
	hljs.highlightAll();
}

running();