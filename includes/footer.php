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
        var data = {"id": id};
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

    function add_to_cart() {
        jQuery('#modal_errors').html("");
        var size = jQuery('#size').val();
        var quantity = jQuery('#quantity').val();
        var available = jQuery('#available').val();
        var error = '';
        var data = jQuery('#add_product_form').serialize();
        if (size == '' || quantity == '' || quantity == 0) {
            error += '<p class="text-danger text-center"> Choisir une taille mémoire et la quantité</p>';
            jQuery('#modal_errors').html(error);
            return;
        } else if (quantity > available) {
            error += '<p class="text-danger text-center"> Il n\'y a que ' + available + ' disponible</p>';
            jQuery('#modal_errors').html(error);
            return;
        } else {
            jQuery.ajax({
                url : '/PA-Mobileshop/admin/parsers/add_cart.php',
                method : 'post',
                data: data,
                success : function(){
                    location.reload();
                },
                error : function(){alert("Quelque chose c\'est mal passé ");}
            });
        }

    }
</script>
</body>
</html>