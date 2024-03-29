<?php
/** @var EP_Competition $competition */
$competition = $GLOBALS['ep_competition']; ?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h3>Sobre nosotros</h3>
                <p>Somos un grupo de amigos que lleva organizando esta porra desde 1994 con la idea de pasar un buen rato mientras se desarrollan Mundiales y Eurocopas. El 100% de la recaudación de la porra se destina a premios para los ganadores. <br />
</p>
            </div>
            <div class="col-md-3">
                <h3>Bar Restaurante Enro</h3>
                <p>Plaza del Duque de Pastrana, 3<br />
                    28036 Madrid<br />
                    España<br />
                    <br />
                    Teléfono: <a href="tel:+34913021004">913 02 10 04</a><br />
                    <br />
                </p>
                <div class="social__icons"> <a href="https://www.twitter.com/<?php echo $competition->getTwitter() ?>" class="socialicon socialicon-twitter"></a> <a href="mailto:<?php echo $competition->getEmail() ?>" class="socialicon socialicon-mail"></a> </div>
            </div>
            <div id="googleMap" class="col-md-3">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12141.345917825292!2d-3.6780913!3d40.4678205!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x35c8bb4c1c782a76!2sEnro!5e0!3m2!1ses!2ses!4v1667674202359!5m2!1ses!2ses" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</footer>
<p class="text-center copyright">&copy; Enroporra <?php echo date("Y") ?>.</p>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo get_stylesheet_directory_uri() ?>/js/bootstrap.min.js"></script>
<script type="text/javascript">

    $('.carousel').carousel({
        interval: 3500, // in milliseconds
        pause: 'none' // set to 'true' to pause slider on mouse hover
    })

</script>
<script type="text/javascript">
    $( "a.submenu" ).click(function() {
        $( ".menuBar" ).slideToggle( "normal", function() {
// Animation complete.
        });
    });
    $( "ul li.dropdown a" ).click(function() {
        $( "ul li.dropdown ul" ).slideToggle( "normal", function() {
// Animation complete.
        });
        $('ul li.dropdown').toggleClass('current');
    });
</script>
<?php wp_footer() ?>
</body>
</html>