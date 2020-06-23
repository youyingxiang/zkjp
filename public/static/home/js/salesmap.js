;(function($){
    // var marks = [{
    //         "city": "1",
    //         "addr": "l1",
    //         "tel": "000",
    //         "x": 154,
    //         "y": 94
    //     },{
    //         "city": "2",
    //         "addr": "l2",
    //         "tel": "0769-82109991",
    //         "x": 346,
    //         "y": 25
    //     },{
    //         "city": "3",
    //         "addr": "l3",
    //         "tel": "0769-82109991",
    //         "x": 355,
    //         "y": 110
    //     },{
    //         "city": "4",
    //         "addr": "l4",
    //         "tel": "0769-82109991",
    //         "x": 277,
    //         "y": 158
    //     },{
    //         "city": "5",
    //         "addr": "l5",
    //         "tel": "0769-82109991",
    //         "x": 174,
    //         "y": 171
    //     },{
    //         "city": "6",
    //         "addr": "l6",
    //         "tel": "0769-82109991",
    //         "x": 180,
    //         "y": 210
    //     },{
    //         "city": "7",
    //         "addr": "l7",
    //         "tel": "0769-82109991",
    //         "x": 236,
    //         "y": 219
    //     },{
    //         "city": "8",
    //         "addr": "l8",
    //         "tel": "0769-82109991",
    //         "x": 285,
    //         "y": 362
    //     },{
    //         "city": "9",
    //         "addr": "l9",
    //         "tel": "0769-82109991",
    //         "x": 338,
    //         "y": 365
    //     },{
    //         "city": "10",
    //         "addr": "r1",
    //         "tel": "0769-82109991",
    //         "x": 676,
    //         "y": 99
    //     },{
    //         "city": "11",
    //         "addr": "r2",
    //         "tel": "0769-82109991",
    //         "x": 801,
    //         "y": 156
    //     },{
    //         "city": "12",
    //         "addr": "r3",
    //         "tel": "0769-82109991",
    //         "x": 574,
    //         "y": 132
    //     },{
    //         "city": "13",
    //         "addr": "r4",
    //         "tel": "0769-82109991",
    //         "x": 529,
    //         "y": 189
    //     },{
    //         "city": "14",
    //         "addr": "r4",
    //         "tel": "0769-82109991",
    //         "x": 643,
    //         "y": 256
    //     },{
    //         "city": "15",
    //         "addr": "r5",
    //         "tel": "0769-82109991",
    //         "x": 722,
    //         "y": 261
    //     },{
    //         "city": "16",
    //         "addr": "b1",
    //         "tel": "0769-82109991",
    //         "x": 479,
    //         "y": 240
    //     },{
    //         "city": "17",
    //         "addr": "b2",
    //         "tel": "0769-82109991",
    //         "x": 453,
    //         "y": 258
    //     },{
    //         "city": "18",
    //         "addr": "b3",
    //         "tel": "0769-82109991",
    //         "x": 474,
    //         "y": 281
    //     },{
    //         "city": "19",
    //         "addr": "b4",
    //         "tel": "0769-82109991",
    //         "x": 427,
    //         "y": 292
    //     },{
    //         "city": "20",
    //         "addr": "b5",
    //         "tel": "0769-82109991",
    //         "x": 456,
    //         "y": 297
    //     },{
    //         "city": "21",
    //         "addr": "b6",
    //         "tel": "0769-82109991",
    //         "x": 524,
    //         "y": 396
    //     }];

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
            _radio = (680 / 980).toFixed(3);
            _elBox.css('overflow-x', 'scroll');
        } else {
            _radio = (_elBox.width() / 980).toFixed(3);
            _elBox.css('overflow-x', 'auto');
        }
        _boxH = 540 * _radio;
        _elBox.css('height', _boxH+ 'px');
        _elCon.css('transform', 'scale(' + _radio + ')');
    }
    $(document).ready(function(){
        // init
        mapData.forEach(function(item){
            _style = 'left:' + item.mapx + 'px; top:' + item.mapy + 'px';
            _markList += '<i title="ZKTeco '+item.city+'" class="salesMap-mark" data-continent="'+item.continent+'" style="' + _style + '"></i>';
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