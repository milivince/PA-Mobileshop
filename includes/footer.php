<footer class="text-center" id="footer">&copy; Copyright 2015-2017 MobileShop</footer>

<!-- Details affichage -->
<script>
    jQuery(window).scroll(function () {
        var vscroll = jQuery(this).scrollTop();
        jQuery('#logotext').css({
            "transform": "translate(0px, " + vscroll / 2 + "px)"
        });

        var vscroll = jQuery(this).scrollTop();

        var vscroll = jQuery(this).scrollTop();
        jQuery('#for-smart').css({
            "transform": "translate(0px, -" + vscroll / 2 + "px)"
        });
    });

    function detailsmodal(id) {
        var data = {"id" : id};
        jQuery.ajax({
            url: '/PA-Mobileshop/includes/detailsmodal.php',
            method: "post",
            data: data,
            success: function (data) {
                jQuery('body').append(data);
                jQuery('#details-modal').modal('toggle');
            },
            error: function () {
                alert("Something went wrong !");
            }

        });

    }
</script>
</body>
</html>