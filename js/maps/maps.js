var map;
(function($) {
  'use strict';
  $('#vmap').vectorMap({
    map: 'world_mill',
    panOnDrag: true,
    focusOn: {
      x: 0.5,
      y: 0.5,
      scale: 1,
      animate: true
    },
    series: {
      regions: [{
        scale: ['#4729b7', '#439aff'],
        normalizeFunction: 'polynomial',
        values: countryData
      }]
    },
	onRegionTipShow: function(e, el, code){
      el.html(el.html()+' ('+countryData[code]+')');
    }
  });
})(jQuery);