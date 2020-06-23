;(function($){
    /*var marks = [{
            "city": "东莞",
            "addr": "东莞市塘厦镇平山区188工业大道",
            "tel": "0769-82109991",
            "x": 620,
            "y": 580
        },{
            "city": "上海",
            "addr": "上海不知什么区",
            "tel": "0769-82109991",
            "x": 730,
            "y": 410
        },{
            "city": "福建",
            "addr": "福建福建福建福建福建福建",
            "tel": "0769-82109991",
            "x": 680,
            "y": 550
        },{
            "city": "南宁",
            "addr": "南宁南宁南宁南宁",
            "tel": "0769-82109991",
            "x": 560,
            "y": 580
        }];*/

    var _elBox = $('#salesMap-box'),
        _elCon = $('#salesMap-con'),
        _elPop = $('#salesMap-pop'),
        _elClose = $('.salesMap-pop-sideBar').eq(0),
        _markList = '', 
        _elCity = $('#markCity'), 
        _elAddr = $('#markAddr'), 
        _elTel = $('#markTel'), 
        _style = '';

    function changeSalesMapSize(){
        var _radio = 0, _boxH = 0;
        if ( _elBox.width() <= 680 ) {
            _radio = (680 / 911).toFixed(3);
            _elBox.css('overflow-x', 'scroll');
        } else {
            _radio = (_elBox.width() / 911).toFixed(3);
            _elBox.css('overflow-x', 'auto');
        }
        _boxH = 708 * _radio;
        _elBox.css('height', _boxH+ 'px');
        _elCon.css('transform', 'scale(' + _radio + ')');
    }
    $(document).ready(function(){
        // init
        mapData.forEach(function(item){
            _style = 'left:' + item.mapx + 'px; top:' + item.mapy + 'px';
            _markList += '<i class="salesMap-mark" data-continent="'+item.continent+'" style="' + _style + '"></i>';
        });
        _elCon.html(_markList);
        changeSalesMapSize();
        // bind
        $(window).resize(function(){
            changeSalesMapSize();
        });
        $('#salesMap-box').on('click', '.salesMap-mark', function(){
            var continentHtml = '';
            var _mark = mapData[$(this).index()];
            var __index = $(this).index();
            var continent = $(this).attr('data-continent');
            mapData.forEach(function(item,index){
                if (item.continent == continent) {
                    if (__index == index) {
                        continentHtml += '<li onclick="changeCity(this)" class="enOn" data_index="'+index+'">'+item.city+'</li>';
                    } else {
                        continentHtml += '<li onclick="changeCity(this)" data_index="'+index+'">'+item.city+'</li>';  
                    }
                }
            });
            $('#continent').html(continentHtml);
            _elBox.attr('role-id', _mark.id);
            _elCity.html(_mark.city);
            _elAddr.html(_mark.address);
            _elTel.html(_mark.tel);
            _elPop.addClass('show');
        });
        _elClose.on('click', function(){
            _elCity.html('');
            _elAddr.html('');
            _elTel.html('');
            _elPop.removeClass('show');
        });
    });
})(jQuery, window);