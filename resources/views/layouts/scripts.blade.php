
<script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/color/jquery.color-2.1.2.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/ja.js"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>


<script>
$(function() {

    $(document).ready(function() {
        // height 最大化
        height_expand();
    });


    $(window).resize(function() {
        // height 最大化
        height_expand();
    });


    ////////////////////////////////////////////////////////////
    /// height 最大化.
    /// target_expand_class_name を指定したクラスをウインドウ最大化させます.
    /// もし  min-height より高さが小さい場合、解除します.
    /// [WARNING] 縦に並べた場合、想定外の動作を行います. 一列に一つまでにしてください.
    /// [WARNING] use only pixel value
    ////////////////////////////////////////////////////////////

    var target_expand_class_name = '.expand-height';
    var default_expand_min_height = 300;

    var height_expand = function() {
        $(target_expand_class_name).each(function(i, dom) {
            do_height_expand(dom);
        });
    };

    var do_height_expand = function(dom_string) {
        var dom = $(dom_string);

        // 現在の座標を取得
        var offset = dom.offset();
        var domHeight = dom.outerHeight(true);  // margin + border + padding + height

        var top    = offset.top      || -1;
        var bottom = top + domHeight || -1;

        // viewport の高さを取得
        var windowHeight = $(window).height();

        // 余りの高さを計算
        var remainHeight = windowHeight - bottom;

        // 要素の高さを取得し、計算後の高さを出力
        var contentHeight = dom.height();   // height only
        var changeHeight  = contentHeight + remainHeight;

        // もし、計算後の値が最小値よりも小さくなる場合は解除
        var minHeight = parseInt(dom.css('min-height')) || default_expand_min_height;
        if (minHeight > changeHeight) {
            dom.css('height', '').css('overflow-y', '');
            return;
        }

        // dom に付与
        dom.css('height', changeHeight + 'px').css('overflow-y', 'scroll');
    };


});
</script>
