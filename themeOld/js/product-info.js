// Highslide
hs.graphicsDir = 'theme/img/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.fadeInOut = true;
hs.outlineType = null;
//    hs.headingEval = 'this.a.title';
//    hs.numberPosition = 'heading';
hs.useBox = true;
hs.width = 435;
hs.height = 433;
hs.showCredits = false;
hs.allowMultipleInstances = false;

hs.registerOverlay({
    overlayId: 'closebutton',
    position: 'top right',
    offsetX: 140,
    offsetY: 0,
    fade: 2 // fading the semi-transparent overlay looks bad in IE
});

hs.addSlideshow({
    //slideshowGroup: 'group1',

    thumbstrip: {
        position: 'rightpanel',
        mode: 'float',
        relativeTo: 'expander',
        width: '150px'
    }
});

var miniGalleryOptions1 = {
    thumbnailId: 'thumb1'
//        wrapperClassName: 'power-wrapper'
};

$(function() {
    $('body').on('click', function(e) {
        if ($('.highslide-wrapper').length && !$(e.target).closest('.highslide-wrapper').length) {
            hs.close();
            e.preventDefault();
            e.stopPropagation();
        }
    });
});