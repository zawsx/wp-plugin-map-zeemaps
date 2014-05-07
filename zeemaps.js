(function() {
	tinymce.PluginManager.requireLangPack('zeemaps');
	tinymce.create('tinymce.plugins.zeemaps', {
		init: function(ed, url) {
			ed.addCommand('zeemaps', function() {
				ed.windowManager.open({
					file:url + "/window.html?_dc=" + Math.random()
					,width:400
					,height:180
					,inline:1
					,title:"Embed a ZeeMap"
				},{
					plugin_url:url
				});
			});

			ed.addButton('zeemaps', {
				title:'Embed a ZeeMap',
				cmd:'zeemaps',
				image:url + '/img/zeemaps.png'
			});

			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('zeemaps', n.nodeName == 'IMG');
			});
		}

		,createControl: function(n, cm) {
			return null;
		}

		,getInfo: function() {
			return {
				longname: 'ZeeMaps plugin'
				,author: 'ZeeMaps'
				,authorurl: 'http://www.zeemaps.com'
				,infourl: 'http://www.zeemaps.com/wordpress'
				,version: 1.2
			};
		}
	});
	tinymce.PluginManager.add('zeemaps', tinymce.plugins.zeemaps);
})();
